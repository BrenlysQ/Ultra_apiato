<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\Amadeus\Actions\SortedResults;
use App\Ship\Parents\Actions\Action;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\FareMasterPricerCalendarOptions;
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
class MasterPricerCalendar extends Task {
	public function run(){
		$currency = (new SelectCurrencyTask)->run(Input::get('currency'));
		$client = CommonActions::buildAmadeusClient($currency,true);
		$cabinOptions = (new CabinOptionTask)->run(Input::get('cabin'));
		$passengers = (new CreatePassengersTask)->run();
		$departure_date = MasterPricerCalendar::parsingDate(Input::get('departure_date'));
		$return_date = MasterPricerCalendar::parsingDate(Input::get('return_date'));    
		$numberOfPassengers = Input::get('adult_count') + Input::get('child_count');
		if(Input::get('trip') == 2){
			$opt = new FareMasterPricerCalendarOptions([  
				'nrOfRequestedPassengers' => $numberOfPassengers,
				'passengers' => $passengers,
				'itinerary' => [
					new MPItinerary([
						'departureLocation' => new MPLocation(['city' => Input::get('departure_city')]),
						'arrivalLocation' => new MPLocation(['city' => Input::get('destination_city')]),
						'date' => new MPDate([
							'dateTime' => new \DateTime($departure_date, new \DateTimeZone('UTC')), // yyyy-mm-dd
							'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
							'range' => 3
						]),
						'airlineOptions' => [
							MPItinerary::AIRLINEOPT_EXCLUDED => ['PU']
						]
					]),
					new MPItinerary([
						'departureLocation' => new MPLocation(['city' => Input::get('destination_city')]),
						'arrivalLocation' => new MPLocation(['city' => Input::get('departure_city')]),
						'date' => new MPDate([
							'dateTime' => new \DateTime($return_date, new \DateTimeZone('UTC')), // yyyy-mm-dd
							'rangeMode' => MPDate::RANGEMODE_MINUS_PLUS,
							'range' => 3
						]),
						'airlineOptions' => [
							MPItinerary::AIRLINEOPT_EXCLUDED => ['PU']
						]
					])
				],
				'currencyOverride' => $currency,
				'cabinClass' => $cabinOptions->cabin,
				'cabinOption' => $cabinOptions->option,
				'doTicketabilityPreCheck' => true
			]);
		}
		if($numberOfPassengers < 10){
			$recommendations = $client->fareMasterPricerCalendar($opt);
			$payload = CommonActions::CreateObject(); 
			$payload->currency = Input::get('currency');
			$payload->cabin = Input::get('cabin');
			$payload->departure_date = $departure_date;
			$payload->return_date = $return_date;
			$payload->passengers = $numberOfPassengers;
			$payload->trip     = Input::get('trip');
			$payload->departure_city = Input::get('departure_city');
			$payload->destination_city = Input::get('destination_city');
			$payload->child_count = Input::get('child_count');
			$payload->inf_count = Input::get('inf_count');
			$payload->adult_count = Input::get('adult_count'); 
			$payload->se = 13; 
			// if(Input::get('log'))
				CommonActions::clientInteraction('fareMasterPricerCalendar',$client);
			if ($recommendations->status === Result::STATUS_OK) {
				$parsed_response = (new MasterCalendarCompressTask())->run($recommendations->response);
				$parsed_response->tagpu = $this->cache($payload,$parsed_response->dates);
				return json_encode($parsed_response);
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
	
	private function cache($payload,$matrix){
		$result = MasterPricerCalendar::array_flatten($matrix);
		$identifier = '3DAYS_AMADEUS-' . str_random(25);
		Cache::put($identifier, json_encode($result), 25);
		return TagsGdsHandler::StoreTag($identifier, $payload);
	}
	
	private static function array_flatten($array) { 
		if (!is_array($array)) { 
			return FALSE; 
		} 
		$result = array(); 
		foreach ($array as $key => $value) { 
			if (is_array($value)) { 
				$result = array_merge($result, array_flatten($value)); 
			}else { 
				$result[$key] = $value; 
			} 
		} 
	  return $result; 
	}
	
	private static function parsingDate($date){
		$date = Carbon::parse($date)->toDateString();
		return $date;
	}
 
}