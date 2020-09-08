<?php

namespace App\Containers\UltraMailer\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraMailer\Models\CampaignStatusModel;

class FullStatusCampaign extends Task
{
    public function run() {
		
		//$name = input('name');//pending//sent//aborted
    
   // $StatusMail = CampaignStatusModel::where('id',$id)->first();
		
		//$id = Input::get('id');
		$name = Input::get('name');

		$tableCampaignStatus = new CampaignStatusModel();
		//$tableCampaignStatus->id = $id;
		$tableCampaignStatus->name = $name;
   		$tableCampaignStatus->save();
    	//dd(json_decode($tableCampaignStatus));
    	/*if( $tableCampaignStatus->save() ){
    var_dump($tableCampaignStatus->id);*/
	  return $tableCampaignStatus;
  	}
}
    