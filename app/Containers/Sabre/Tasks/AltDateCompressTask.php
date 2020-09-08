<?php

namespace App\Containers\Sabre\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Commons\CommonActions;
use App\Containers\Sabre\Data;
use App\Containers\Sabre\Tasks\FligthExistTask;
use Carbon\Carbon;
/**
 * Class AltDateCompressTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AltDateCompressTask extends Task
{
	public $compressed = array();
	public function run($response){

		//dd($response);
		if(property_exists($response->Body->OTA_AirLowFareSearchRS, 'PricedItineraries')){
			//print_r($response->Body->OTA_AirLowFareSearchRS->PricedItineraries); die;
			if(is_array($response->Body->OTA_AirLowFareSearchRS->PricedItineraries->PricedItinerary)){
				foreach ($response->Body->OTA_AirLowFareSearchRS->PricedItineraries->PricedItinerary as $key => $priced_itinerary) {
					//print_r($priced_itinerary); die;
					$this->getPriced($priced_itinerary);
				}
			}else{
				$this->getPriced($response->Body->OTA_AirLowFareSearchRS->PricedItineraries->PricedItinerary);
			}

		}
		return $this->compressed; 
	}
	public function getPriced($priced_itinerary){
		$itinerary = $priced_itinerary->AirItinerary->OriginDestinationOptions;
		$outseg = $this->getSegment($itinerary->OriginDestinationOption[0]);
		$retseg = $this->getSegment($itinerary->OriginDestinationOption[1]);
		//
		$time_departure = $this->getDate($outseg->DepartureDateTime);
		$time_return = $this->getDate($retseg->DepartureDateTime);
		$this->compressed[(string)$time_return][(string)$time_departure] = $priced_itinerary;
	}
	public function getSegment($odo){
		(!is_array($odo->FlightSegment)) ? $ret = $odo->FlightSegment : $ret = $odo->FlightSegment[0];
		return $ret;
	}
	public function getDate($date){
		$dt = Carbon::parse($date);
		$dt->hour = 0;
		$dt->minute = 0;
		$dt->second = 0;
		return $dt->timestamp;
	}
}
