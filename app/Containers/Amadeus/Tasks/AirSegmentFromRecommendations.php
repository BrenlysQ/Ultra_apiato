<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;  
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\AirSellFromRecommendationOptions;
use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Itinerary;
use Amadeus\Client\RequestOptions\Air\SellFromRecommendation\Segment;
use Illuminate\Support\Facades\Storage;
use App\Commons\CommonActions;
use App\Mail\BookErrorMail;
class AirSegmentFromRecommendations extends Task {
	public function run($cache,$client){
		$segmentsOutbound = AirSegmentFromRecommendations::createSegments(
			$cache->itinerary,
			$cache->flight->outbound->segments,
			$cache->flight->outbound->dtime
		);
		if($cache->flight->return){
			$segmentsReturn = AirSegmentFromRecommendations::createSegments(
				$cache->itinerary,
				$cache->flight->return->segments,
				$cache->flight->return->dtime
			);
			$opt = new AirSellFromRecommendationOptions([
				'itinerary' => [
					new Itinerary([
						'from' => $cache->flight->outbound->origin->IATA,
						'to' => $cache->flight->outbound->destination->IATA,
						'segments' => $segmentsOutbound
					]),
					new Itinerary([ 
						'from' => $cache->flight->return->origin->IATA,
						'to' => $cache->flight->return->destination->IATA,
						'segments' => $segmentsReturn
					])
				]
			]);
		}else{
			$opt = new AirSellFromRecommendationOptions([
				'itinerary' => [
					new Itinerary([
						'from' => $cache->flight->outbound->origin->IATA,
						'to' => $cache->flight->outbound->destination->IATA,
						'segments' => $segmentsOutbound
					])
				]
			]);
		}
		$sellResult = $client->airSellFromRecommendation($opt);
		//CommonActions::clientInteraction('airSellFromRecommendation',$client);
		if ($sellResult->status === Result::STATUS_OK) {
			$pnrCreated = (new CreatePNR())->run(
				json_encode($sellResult->response),
				$client,
				$cache
			);
			return $pnrCreated;
		}else{
			$itinerary = $cache->itinerary;
			Mail::to('plusultradesarrollo@gmail.com')->send(new BookErrorMail($itinerary));
			return array(
				"amadeus"  => true, 
				"status"   => 'Ha Ocurrido un Error (airSellFromRecommendation)',
				"messages" => 'Este vuelo ya no esta disponible, por favor seleccione otro',
				"result"   => $sellResult->response
			);
		}
	}
	public static function createSegments($itinerary,$segments,$date){
		$seg = array();
		foreach ($segments as $key => $segment) {
			//Validating airline IATA
			if(property_exists($segment->destination,'IATA')){
				$destination = $segment->destination->IATA;
			}else{
				$destination = $segment->destination;
			}
			if(property_exists($segment->origin,'IATA')){
				$origin = $segment->origin->IATA;
			}else{
				$origin = $segment->origin;
			}
			//Validating Type of Classbook
			if(is_array($segment->classbook)){
				$classbook = $segment->classbook[$key];
			}else{
				$classbook = $segment->classbook;
			}
			$seg[$key] = new Segment([ 
                    'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s',$date, new \DateTimeZone('UTC')),
                    'from' => $origin,
                    'to' => $destination,
                    'companyCode' => $segment->airline->iata,
                    'flightNumber' => $segment->flightno,
                    'bookingClass' => $classbook,
                    'nrOfPassengers' => $itinerary->adult_count + $itinerary->child_count,
                    'statusCode' => Segment::STATUS_SELL_SEGMENT
            ]);
		}
		return $seg;
	}
}