<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Commons\Airports;
class CreateSegmentTask extends Task {
	public $segmentFlight = array();
	public function run($flights,$reference,$seqnumber,$class,$fareTypes,$return = false){
		if(is_object($class)){
			print_r($class); die;
		}
		if (is_array($flights)){
			foreach($flights as $key => $flight){
				if($flight->propFlightGrDetail->flightProposal[0]->ref == $reference->refNumber){
					if(isset($class['productInformation'])){
						$class = $class['productInformation'];
					}
					if(isset($fareTypes['productInformation'])){
						$fareTypes = $fareTypes['productInformation'];
					}
					$airlinesAndSegments = (new AirlinesSegmentsTask())->run($flight,$class,$fareTypes);
					$response = CommonActions::CreateObject();
					if(count($airlinesAndSegments['segments']) > 1){ 
						$first = $airlinesAndSegments['segments'][0];
						$last = $airlinesAndSegments['segments'][(count($airlinesAndSegments['segments'])) - 1];
						$response->dtime = $first['dtime'];
						$response->atime = $last['atime']; 
					}else{
						$first = $airlinesAndSegments['segments'][0];
						$last = $airlinesAndSegments['segments'][0];
						$response->dtime = $first['dtime']; 
						$response->atime = $last['atime'];
					}
					if(!$return){
						$response->destination = (new Airports(Input::get('destination_city')))->BasicInfo();
						$response->origin = (new Airports(Input::get('departure_city')))->BasicInfo();
					}else{
						$response->destination = (new Airports(Input::get('departure_city')))->BasicInfo();
						$response->origin = (new Airports(Input::get('destination_city')))->BasicInfo();
					}
					//dd($flight->flightDetails[]->flightInformation->location[0]->locationId);
					$response->airlines = $airlinesAndSegments['airlines'];
					$response->elapsedtime = '';
					$response->segments = $airlinesAndSegments['segments'];
					$response->seqnumber = $seqnumber;
					if(count($airlinesAndSegments['segments']) == 1){
						$response->stops = false;
					}else{
						$response->stops = $airlinesAndSegments['stops'];
					}
					
					$this->segmentFlight[0] = $response;
				}
			}
		}else{
			if($flights->propFlightGrDetail->flightProposal[0]->ref == $reference->refNumber){
				//Task que retorne Aerolinea y segmentos
				$airlinesAndSegments = (new AirlinesSegmentsTask())->run($flights,$class,$fareTypes);
				$response = CommonActions::CreateObject();
				if(count($airlinesAndSegments['segments']) > 1){ 
					$first = $airlinesAndSegments['segments'][0];
					$last = $airlinesAndSegments['segments'][(count($airlinesAndSegments['segments'])) - 1];
					$response->dtime = $first['dtime'];
					$response->atime = $last['atime']; 
				}else{
					$first = $airlinesAndSegments['segments'][0];
					$last = $airlinesAndSegments['segments'][0];
					$response->dtime = $first['dtime'];
					$response->atime = $last['atime'];
				}	
				$response->airlines = $airlinesAndSegments['airlines'];
				$response->destination = (new Airports(Input::get('destination_city')))->BasicInfo();
				$response->elapsedtime = '';
				$response->origin = (new Airports(Input::get('departure_city')))->BasicInfo();
				$response->segments = $airlinesAndSegments['segments'];
				$response->seqnumber = $seqnumber;
				$response->stops = '';
				$this->segmentFlight[0] = $response;
			}
		}
		return $this->segmentFlight;
	}
}