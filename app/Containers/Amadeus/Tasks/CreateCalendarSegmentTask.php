<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Commons\Airports;
class CreateCalendarSegmentTask extends Task {
	public $segmentFlight = array();
	public function run($flight,$class,$fareTypes,$seqnumber){
		if(isset($class['productInformation'])){
			$class = $class['productInformation'];
		}
		if(isset($fareTypes['productInformation'])){
			$fareTypes = $fareTypes['productInformation'];
		} 
		$airlinesAndSegments = (new CalendarAirlinesSegmentsTask())->run($flight,$class,$fareTypes);
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
		$response->destination = (new Airports(Input::get('departure_city')))->BasicInfo();
		$response->origin = (new Airports(Input::get('destination_city')))->BasicInfo();
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
		return $this->segmentFlight;
	}
}