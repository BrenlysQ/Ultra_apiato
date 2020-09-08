<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Amadeus\Actions\SortedResults;
use App\Ship\Parents\Actions\Action;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\RequestOptions\MpBaseOptions;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Amadeus\Client\RequestOptions\Fare\MPItinerary;
use Amadeus\Client\RequestOptions\Fare\MPDate;
use Amadeus\Client\RequestOptions\Fare\MPLocation;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;  
use App\Commons\TagsGdsHandler;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class MasterPricerTravelBoard extends Task {
	public function run(){
        //ini_set('default_socket_timeout', 600);
        ini_set('memory_limit','512M');
		$trip = MasterPricerTravelBoard::infoTrip();
		$client = CommonActions::buildAmadeusClient($trip->currency,true);
		if(Input::get('trip') == 2){
				$opt = new FareMasterPricerTbSearch([  
					'nrOfRequestedPassengers' => $trip->numberOfPassengers,
					'passengers' => $trip->passengers,
					'itinerary' => [
						new MPItinerary([
							'departureLocation' => new MPLocation(['city' => Input::get('departure_city')]),
							'arrivalLocation' => new MPLocation(['city' => Input::get('destination_city')]),
							'date' => new MPDate([
								'dateTime' => new \DateTime($trip->departure_date, new \DateTimeZone('UTC')) // yyyy-mm-dd
							]),
							'airlineOptions' => [
								MPItinerary::AIRLINEOPT_EXCLUDED => ['PU']
							]
						]),
						new MPItinerary([
							'departureLocation' => new MPLocation(['city' => Input::get('destination_city')]),
							'arrivalLocation' => new MPLocation(['city' => Input::get('departure_city')]),
							'date' => new MPDate([
								'dateTime' => new \DateTime($trip->return_date, new \DateTimeZone('UTC')) // yyyy-mm-dd
							]),
							'airlineOptions' => [
								MPItinerary::AIRLINEOPT_EXCLUDED => ['PU']
							]
						])
					],
					'flightOptions' => [
						MpBaseOptions::FLIGHTOPT_PUBLISHED, //Tarifa Publica
						MpBaseOptions::FLIGHTOPT_UNIFARES //Tarifa Privada
					],
					'currencyOverride' => $trip->currency,
					'cabinClass' => $trip->cabinOptions->cabin,
					'cabinOption' => $trip->cabinOptions->option,
					'doTicketabilityPreCheck' => true
				]);
		}else{
			$opt = new FareMasterPricerTbSearch([  
				'nrOfRequestedResults' => 20,
				'nrOfRequestedPassengers' => $trip->numberOfPassengers,
				'passengers' => $trip->passengers,
				'itinerary' => [
					new MPItinerary([ 
						'departureLocation' => new MPLocation(['city' => Input::get('departure_city')]),
						'arrivalLocation' => new MPLocation(['city' => Input::get('destination_city')]),
						'date' => new MPDate([
							'dateTime' => new \DateTime($trip->departure_date, new \DateTimeZone('UTC')) // yyyy-mm-dd
						]),
						'airlineOptions' => [
							MPItinerary::AIRLINEOPT_EXCLUDED => ['PU']
						]
					])
				],
				'flightOptions' => [
					MpBaseOptions::FLIGHTOPT_PUBLISHED, //Tarifa Publica
					MpBaseOptions::FLIGHTOPT_UNIFARES //Tarifa Privada
				],
				'currencyOverride' => $trip->currency,
				'cabinClass' => $trip->cabinOptions->cabin,
				'cabinOption' => $trip->cabinOptions->option,  
				'doTicketabilityPreCheck' => true
			]);
		}
		if($trip->numberOfPassengers < 10){
			$recommendations = $client->fareMasterPricerTravelBoardSearch($opt);
			$payload = (new PayloadTask())->run(
				$trip->departure_date,
				$trip->return_date,
				$trip->numberOfPassengers
			); 
			//echo $client->getLastRequest(); die;
			// if(Input::get('log'))
				CommonActions::clientInteraction('fareMasterPricerTravelBoardSearch',$client);
			if ($recommendations->status === Result::STATUS_OK) {
				$tosrted = CommonActions::CreateObject();
				$tosrted->payload = $payload;
				//print_r($recommendations); die;
				$result = new SortedResults($recommendations->response, $tosrted,$trip->numberOfPassengers);
				$result->tagpu = MasterPricerTravelBoard::cache($payload,$result);
				return json_encode($result);
			}else{
				$response = CommonActions::CreateObject();
				$response->status = 'Ha Ocurrido Un Problema';
				$response->result = $recommendations->response;
				return json_encode($response);
			}
		}else{
			$response = CommonActions::CreateObject();
			$response->status = 'Ha Ocurrido Un Problema';
			$response->result = 'El numero de pasajeros es mayor a la cantidad permitida';
			return json_encode($response);
		}
	}
	private static function cache($payload,$response){
	    $identifier = 'AMADEUS-' . str_random(25);
	    Cache::put($identifier, json_encode($response), 25);
	    return TagsGdsHandler::StoreTag($identifier, $payload);
	} 
	
	private static function parsingDate($date){
		$date = Carbon::parse($date)->toDateString();
		return $date;
	}

	public static function infoTrip(){
		$data = CommonActions::CreateObject(); 
		$data->currency = (new SelectCurrencyTask)->run(Input::get('currency'));
		$data->cabinOptions = (new CabinOptionTask)->run(Input::get('cabin'));
		$data->passengers = (new CreatePassengersTask)->run();
		$data->departure_date = MasterPricerTravelBoard::parsingDate(Input::get('departure_date'));
		$data->return_date = MasterPricerTravelBoard::parsingDate(Input::get('return_date'));    
		$data->numberOfPassengers = Input::get('adult_count') + Input::get('child_count');
		return $data;
	}
	
}