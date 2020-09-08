<?php

namespace App\Containers\UltraMailer\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraMailer\Models\CampaignsModel;


class FullCampaignTable extends Task
{
    public function run() {
    	//dd("Alloooo bien2");
	//	$id = Input::get('id');
    	//dd(Input::get());
		$title = Input::get('title');
		$type = Input::get('type');
		$sendby = Input::get('sent_by');
		$datend = Input::get('date_end');
        $status = Input::get('status');
        //dd(Input::get());
		
		//$createCampaign = CampaignsModel::where('id',$id)->first();
		$tablecampaign = new CampaignsModel();
		//$tablecampaign->id = $id;
		$tablecampaign->title = $title;
		$tablecampaign->type = $type;
		$tablecampaign->sent_by = $sendby; 
		$tablecampaign->date_end = $datend;
		$tablecampaign->status = $status ;

		//dd(json_decode($tablecampaign->status));
		$tablecampaign->save();
	  	return $tablecampaign;
	}
}