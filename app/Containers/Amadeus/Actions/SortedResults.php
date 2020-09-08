<?php

namespace App\Containers\Amadeus\Actions;
use App\Ship\Parents\Actions\Action;
use DateTime;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Commons\CommonActions;
use App\Containers\Amadeus\Actions\TravelOption;
use App\Containers\Amadeus\Tasks\CreateSegmentTask;
use App\Containers\Amadeus\Tasks\CreatePricetTask;
use App\Containers\Amadeus\Tasks\ConstructClassTask;
use App\Containers\Amadeus\Tasks\ConstructFareTypeTask;
class SortedResults extends Action{
  public $flights;
  public $flightscount;
  public function __construct($itineraries,$data,$numberOfPassangers){
    $this->flights = array();
    $this->flightscount = 0;
	if(!is_object($itineraries->recommendation)){
		foreach ($itineraries->recommendation as $key => $option) {		
		  foreach ($option->segmentFlightRef as $i => $flight){
			if((Input::get('inf_count') == 0) && (Input::get('child_count') == 0)){
			  $classes = $this->call(ConstructClassTask::class,[$option]);
			  $fareTypes = $this->call(ConstructFareTypeTask::class,[$option]);
			}else{
			  $classes = $this->call(ConstructClassTask::class,[
				$option,
				$paxes = true
			  ]);
			  $fareTypes = $this->call(ConstructFareTypeTask::class,[
				$option,
				$paxes = true
			  ]);  
			}
			if(!is_object($flight)){
			  //A veces flight no es un Objeto, sino un Array
			  if((is_object($itineraries->flightIndex)) &&(Input::get('trip') == 1)){
				$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
					$itineraries->flightIndex->groupOfFlights,     
					$flight[0],
					$flight[0]->refNumber,
					$classes['outbound'],
					$fareTypes['outbound']
				]);   
			  }else{
				$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
					$itineraries->flightIndex[0]->groupOfFlights,     
					$flight[0],
					$flight[0]->refNumber,
					$classes['outbound'],
					$fareTypes['outbound']
				]);  
			  }
			  if(Input::get('trip') == 2){
				  $this->flights[$this->flightscount]['return'] = $this->call(CreateSegmentTask::class,[
					$itineraries->flightIndex[1]->groupOfFlights,     
					$flight[1],
					$flight[1]->refNumber,
					$classes['return'],
					$fareTypes['return'],
					$return = true
				  ]);
			  }
			}elseif(isset($flight->referencingDetail) && is_array($flight->referencingDetail)){
				if((is_object($itineraries->flightIndex)) && (Input::get('trip') == 1)){
					$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
						$itineraries->flightIndex->groupOfFlights,     
						$flight->referencingDetail[0],
						$flight->referencingDetail[0]->refNumber,
						$classes['outbound'],
						$fareTypes['outbound']
					]);
				}else{
					$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
						$itineraries->flightIndex[0]->groupOfFlights,     
						$flight->referencingDetail[0],
						$flight->referencingDetail[0]->refNumber,
						$classes['outbound'],
						$fareTypes['outbound']
					]);
				}
				if(Input::get('trip') == 2){
					$this->flights[$this->flightscount]['return'] = $this->call(CreateSegmentTask::class,[
						$itineraries->flightIndex[1]->groupOfFlights,     
						$flight->referencingDetail[1],
						$flight->referencingDetail[1]->refNumber,
						$classes['return'],
						$fareTypes['return'],
						$return = true
					]);
				}
			}
			$this->flights[$this->flightscount]['price'] = $this->call(CreatePricetTask::class,[
			  $option,
			  $key + 1
			]);
			$this->flightscount = $this->flightscount + 1;
		  }
		}  
	}else{
		$option = $itineraries->recommendation;
		foreach ($option->segmentFlightRef as $i => $flight){
			if((Input::get('inf_count') == 0) && (Input::get('child_count') == 0)){
			  $classes = $this->call(ConstructClassTask::class,[$option]);
			  $fareTypes = $this->call(ConstructFareTypeTask::class,[$option]);
			}else{
			  $classes = $this->call(ConstructClassTask::class,[
				$option,
				$paxes = true
			  ]);
			  $fareTypes = $this->call(ConstructFareTypeTask::class,[
				$option,
				$paxes = true
			  ]);  
			}
			if(!is_object($flight)){
			  //A veces flight no es un Objeto, sino un Array
			  if((is_object($itineraries->flightIndex)) &&(Input::get('trip') == 1)){
				$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
					$itineraries->flightIndex->groupOfFlights,     
					$flight[0],
					$flight[0]->refNumber,
					$classes['outbound'],
					$fareTypes['outbound']
				]);   
			  }else{
				$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
					$itineraries->flightIndex[0]->groupOfFlights,     
					$flight[0],
					$flight[0]->refNumber,
					$classes['outbound'],
					$fareTypes['outbound']
				]);  
			  }
			  if(Input::get('trip') == 2){
				  $this->flights[$this->flightscount]['return'] = $this->call(CreateSegmentTask::class,[
					$itineraries->flightIndex[1]->groupOfFlights,     
					$flight[1],
					$flight[1]->refNumber,
					$classes['return'],
					$fareTypes['return'],
					$return = true
				  ]);
			  }
			}elseif(isset($flight->referencingDetail) && is_array($flight->referencingDetail)){
				if((is_object($itineraries->flightIndex)) && (Input::get('trip') == 1)){
					$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
						$itineraries->flightIndex->groupOfFlights,     
						$flight->referencingDetail[0],
						$flight->referencingDetail[0]->refNumber,
						$classes['outbound'],
						$fareTypes['outbound']
					]);
				}else{
					$this->flights[$this->flightscount]['outbound'] = $this->call(CreateSegmentTask::class,[
						$itineraries->flightIndex[0]->groupOfFlights,     
						$flight->referencingDetail[0],
						$flight->referencingDetail[0]->refNumber,
						$classes['outbound'],
						$fareTypes['outbound']
					]);
				}
				if(Input::get('trip') == 2){
					$this->flights[$this->flightscount]['return'] = $this->call(CreateSegmentTask::class,[
						$itineraries->flightIndex[1]->groupOfFlights,     
						$flight->referencingDetail[1],
						$flight->referencingDetail[1]->refNumber,
						$classes['return'],
						$fareTypes['return'],
						$return = true
					]);
				}
			}
			$this->flights[$this->flightscount]['price'] = $this->call(CreatePricetTask::class,[
			  $option,
			  $key + 1
			]);
			$this->flightscount = $this->flightscount + 1;
		}
	}
  }
}

