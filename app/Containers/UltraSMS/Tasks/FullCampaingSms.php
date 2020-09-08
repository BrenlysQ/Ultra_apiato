<?php

namespace App\Containers\UltraSMS\Tasks;
use Mail;
use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Tasks\Task;
use App\Mail\UltraMailerNotification;
use App\Containers\UltraSMS\Models\UltraMailer;
use App\Containers\UltraSMS\Models\SmsTypeModel;

class FullCampaingSms extends Task
{
    public function run() {
		
		$id_campaign = Input::get('id_campaign');
		$receptores = Input::get('receptores');//mcy//css//vln
		$message = Input::get('message');
		$api=Input::get('api');

		//$SmsCampaign =SmsTypeModel::where('id',$id)->first();
      $Sms = new SmsTypeModel();
      $Sms->id_campaign = $id_campaign;
      $Sms->receptores = $receptores;
      $Sms->message = $message;
      $Sms->api = $api;
      $Sms->save();
      
      //dd($Sms);
    return $Sms;
    }
}