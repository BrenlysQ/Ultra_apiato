<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Mail;
use App\Mail\RemindSendPnrMail;


class UpdatePnrTask extends Task
{
    public function run($data)
    {

      $itin = Input::get('itinerary');
      $paxes = Input::get('paxes');
      $itinerary = ItinModel::where('id',$itin)->first();
			if($itinerary->itinerary->se == 13){
				$paxesToModify = json_encode($paxes);
				$footprints = UpdatePnrTask::encodePaxes($paxesToModify);
				$paxes = UpdatePnrTask::amadeusPaxes($paxesToModify,$footprints);
				$itinerary->paxes = json_encode($paxes);
			}else{
				$itinerary->paxes = json_encode($paxes);  
			}
			$itinerary->status = 2;
			return $itinerary;
    }
	private static function encodePaxes($paxes_details){
		$paxes_details = json_decode($paxes_details);
		$paxes = array();
		foreach($paxes_details as $pax){
			$paxes[] = md5(
				strtolower($pax->firstname).
				strtolower($pax->lastname).
				strtoupper($pax->type)
			);
		}
		return $paxes;
	}
	
	private static function amadeusPaxes($paxes_details,$paxes_footprint){
		$paxes_details = json_decode($paxes_details);
		foreach($paxes_details as $key => $pax){
			if(count($paxes_footprint) == 1){
				$pax->footprint = $paxes_footprint[0];
			}else{
				$pax->footprint = $paxes_footprint[$key];
			}
		}
		return $paxes_details;
	}
}	
