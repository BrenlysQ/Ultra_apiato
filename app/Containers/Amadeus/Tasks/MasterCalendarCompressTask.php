<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;  
use Carbon\Carbon;
class MasterCalendarCompressTask extends Task {
	public $matrix = array(array());
	public $dates = array();
	private $departure_date;
	private $return_date;

	public function run($itineraries){
		MasterCalendarCompressTask::subDays();
		MasterCalendarCompressTask::matrixInizializer();
		foreach ($itineraries->recommendation as $key => $option) {
			if(isset($option->paxFareProduct)){
				if((Input::get('inf_count') == 0) && (Input::get('child_count') == 0)){
				  $classes   = (new ConstructClassTask)->run($option);
				  $fareTypes = (new ConstructFareTypeTask)->run($option);
				}else{
					$classes = (new ConstructClassTask)->run(
						$option,
						$paxes = true
					);
					$fareTypes = (new ConstructFareTypeTask)->run(
						$option,
						$paxes = true
					);  
				}
				if(is_array($option->segmentFlightRef)){
					$flight = MasterCalendarCompressTask::flightBuilder(
						$itineraries,
						$option->segmentFlightRef[0],
						$classes,
						$fareTypes,
						$option,
						$key
					);
				}else{
					$flight = MasterCalendarCompressTask::flightBuilder(
						$itineraries,
						$option->segmentFlightRef,
						$classes,
						$fareTypes,
						$option,
						$key
					);
				}
				$price = (new CreatePricetTask())->run(
					$option,
					$key + 1
				);
				$positions = MasterCalendarCompressTask::getFlightPosition($flight);
				$this->matrix[$positions->outbound][$positions->return]->odo = $flight;
				$this->matrix[$positions->outbound][$positions->return]->price = $price;
			}
		}
		$response = CommonActions::CreateObject();
		$response->dates = $this->matrix;
		$response->from_amadeus = $itineraries;
		$response->outbound = $this->departure_date;
		$response->return = $this->return_date;
		return $response;
	}
	
	private static function flightBuilder(
		$itineraries,
		$flightRef,
		$classes,
		$fareTypes,
		$option,
		$key
	){
		if(isset($flightRef->referencingDetail) && is_array($flightRef->referencingDetail)){
			$outbound = MasterCalendarCompressTask::legsBuilder(
				$itineraries->flightIndex[0],
				$flightRef->referencingDetail[0]->refNumber,
				$classes['outbound'],
				$fareTypes['outbound'],
				$outbound = true
			);
			$return = MasterCalendarCompressTask::legsBuilder(
				$itineraries->flightIndex[1],
				$flightRef->referencingDetail[1]->refNumber,
				$classes['return'],
				$fareTypes['return']
			);
		}elseif(isset($flightRef->referencingDetail) && is_object($flightRef->referencingDetail)){
			$outbound = MasterCalendarCompressTask::legsBuilder(
				$itineraries->flightIndex[0],
				$flightRef->referencingDetail->refNumber,
				$classes['outbound'],
				$fareTypes['outbound'],
				$outbound = true
			); 
			$return = MasterCalendarCompressTask::legsBuilder(
				$itineraries->flightIndex[1],
				$flightRef->referencingDetail->refNumber,
				$classes['return'],
				$fareTypes['return']
			);
		}else{
			$outbound = MasterCalendarCompressTask::legsBuilder(
				$itineraries->flightIndex[0],
				$flightRef->refNumber[0],
				$classes['outbound'],
				$fareTypes['outbound'],
				$outbound = true
			);
			$return = MasterCalendarCompressTask::legsBuilder(
				$itineraries->flightIndex[1],
				$flightRef->refNumber[1],
				$classes['return'],
				$fareTypes['return']
			);
		}
		$price = (new CreatePricetTask())->run(
			$option,
			$key + 1
		);
		$flight = CommonActions::CreateObject();
		$flight->outbound = $outbound;
		$flight->return   = $return;
		$flight->price    = $price;
		return $flight;
	}

	private static function legsBuilder(
		$flight,
		$reference,
		$classes,
		$fareTypes,
		$outbound = false
	){
		$flight_details = $flight->groupOfFlights[$reference - 1]->flightDetails;
		
		$leg = (new CreateCalendarSegmentTask)->run(
			$flight_details,
			$classes,
			$fareTypes,
			$reference,
			$outbound
		);
		return $leg;
	}
	 
	private function matrixInizializer(){
		$departure_date = $this->departure_date;
		$return_date = $this->return_date;
		$initret = $this->return_date->toDateString();
		$initdep = $this->departure_date->toDateString();
		for($i = 0;$i < 7;$i++){
			if($i == 0){
				$departure_date = $departure_date->addDays($i);
			}else{
				$departure_date = $departure_date->addDays(1);
				$return_date = Carbon::parse($initret);
			}
			for($j = 0;$j < 7;$j++){
				if($j == 0){
					$return_date = $return_date->addDays($j);
				}else{
					$return_date = $return_date->addDays(1);
				}
				$content = CommonActions::CreateObject();
				$content->departure_date = \DateTime::createFromFormat('Y-m-d',$departure_date->toDateString(), new \DateTimeZone('UTC'));
				$content->odo = null;
				$content->price = null;
				$content->return_date = \DateTime::createFromFormat('Y-m-d',$return_date->toDateString(), new \DateTimeZone('UTC'));
				$this->matrix[$i][$j] = $content;
			}
		}
		$this->departure_date = Carbon::parse($initdep);
		$this->return_date = Carbon::parse($initret);
	}

	private static function constructDate($amdate){
		$amdate = explode(" ", $amdate);
		$date = str_split($amdate[0], 2);
		$hour = str_split($amdate[1], 2); 
		$dt = Carbon::create(($date[2]+2000), $date[1], $date[0], $hour[0], $hour[1])->toDateTimeString();
		return $dt;
	}
	
	private static function parsingDate($date){
		$date = Carbon::parse($date)->toDateString();
		return $date;
	}
	
	private function getFlightPosition($flight){
		if(is_array($flight->outbound)){
			$fd = $flight->outbound[0]->dtime;
		}else{
			$fd = $flight->outbound->dtime;
		}
		if(is_array($flight->return)){
			$fr = $flight->return[0]->dtime;
		}else{
			$fr = $flight->return->dtime;
		}
		$position = CommonActions::CreateObject();
		$position->outbound = Carbon::parse($fd)->diffInDays($this->departure_date);
		$position->return   = Carbon::parse($fr)->diffInDays($this->return_date);
		return $position;
	}
	
	private function subDays(){
		$this->departure_date = MasterCalendarCompressTask::parsingDate(Input::get('departure_date'));
		$this->return_date = MasterCalendarCompressTask::parsingDate(Input::get('return_date')); 
		$this->departure_date = Carbon::parse($this->departure_date)->subDays(3);
		$this->return_date = Carbon::parse($this->return_date)->subDays(3);
	}
	
}