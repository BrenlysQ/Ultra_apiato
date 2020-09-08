<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use  Carbon\Carbon;
use App\Commons\Airline;
use App\Commons\Airports;

class AirlinesSegmentsTask extends Task {
	public $airlines = array();
	public $stops = array();
	public $stops_array = array();
	public function run($flight,$class,$fareTypes){
		$segments = array();
		if(is_array($flight->flightDetails)){
			$fLength = count($flight->flightDetails);
			foreach($flight->flightDetails as $i => $segment){
				$destination = (new Airports($segment->flightInformation->location[1]->locationId));
				$origin = (new Airports($segment->flightInformation->location[0]->locationId));
				$segments[$i]['airline'] = (new Airline($segment->flightInformation->companyId->marketingCarrier))->BasicInfo();
				$segments[$i]['atime'] = AirlinesSegmentsTask::constructDate($segment->flightInformation->productDateTime->dateOfArrival.' '.$segment->flightInformation->productDateTime->timeOfArrival);
				$segments[$i]['baggage'] = '';
				$segments[$i]['classbook'] = $class[$i];
				$segments[$i]['fareTypes'] = $fareTypes[$i];
				$segments[$i]['destination'] = $destination->data;
				$segments[$i]['dtime'] = AirlinesSegmentsTask::constructDate($segment->flightInformation->productDateTime->dateOfDeparture.' '.$segment->flightInformation->productDateTime->timeOfDeparture);
				$segments[$i]['elapsedtime'] = '';
				$segments[$i]['flightno'] = $segment->flightInformation->flightOrtrainNumber;
				$segments[$i]['origin'] = $origin->data;
				$airline = $segments[$i]['airline'];
				if(!in_array($airline->iata,$this->airlines)){
					$this->airlines[] = $airline->iata;
				}
				(is_object($segments[$i]['destination']))? $iata_air = $segments[$i]['destination']->IATA : $iata_air = $segments[$i]['destination'];
				if(!in_array($iata_air,$this->stops_array) && $i < $fLength -1){
					$this->stops_array[] = $iata_air;
					$this->stops[] = array('airport' => $segments[$i]['destination']);
				}
			}
			return array(
				"airlines" => $this->airlines,
				"segments" => $segments,
				"stops" => $this->stops
			); 
		}else{
			if(is_object($class)){dd($class);}
			$segment = $flight->flightDetails; 
			$segments[0]['airline'] = (new Airline($segment->flightInformation->companyId->marketingCarrier))->BasicInfo();
			$segments[0]['atime'] = AirlinesSegmentsTask::constructDate($segment->flightInformation->productDateTime->dateOfArrival.' '.$segment->flightInformation->productDateTime->timeOfArrival);
			$segments[0]['baggage'] = '';
			$segments[0]['classbook'] = $class;	
			$segments[0]['fareTypes'] = $fareTypes;	
			$segments[0]['destination'] = $segment->flightInformation->location[1]->locationId;
			$segments[0]['dtime'] = AirlinesSegmentsTask::constructDate($segment->flightInformation->productDateTime->dateOfDeparture.' '.$segment->flightInformation->productDateTime->timeOfDeparture);
			$segments[0]['elapsedtime'] = '';
			$segments[0]['flightno'] = $segment->flightInformation->flightOrtrainNumber;
			$segments[0]['origin'] = $segment->flightInformation->location[0]->locationId;
			if(!in_array($segments[0]['airline']->iata,$this->airlines)){
				$this->airlines[0] = $segments[0]['airline']->iata;
			}
			//print_r($segments[0]['destination']); die;
			(is_object($segments[0]['destination']))? $iata_air = $segments[0]['destination']->IATA : $iata_air = $segments[0]['destination'];
			if(!in_array($iata_air,$this->stops_array)){
					$this->stops[] = array('airport' => $segments[0]['destination']);
					$this->stops_array[] = $iata_air;
			}
			return array(
				"airlines" => $this->airlines,
				"segments" => $segments,
				"stops" => $this->stops
			);
		}
	}
	
	public function constructDate($amdate){
		$amdate = explode(" ", $amdate);
		$date = str_split($amdate[0], 2);
		$hour = str_split($amdate[1], 2);
		$dt = Carbon::create(($date[2]+2000), $date[1], $date[0], $hour[0], $hour[1])->toDateTimeString();
		return $dt;
	}
	
}