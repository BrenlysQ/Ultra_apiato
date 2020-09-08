<?php

namespace App\Containers\UltraMailer\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraMailer\Models\MailCampaignModel;

class FullMailCampaign extends Task
{
    public function run() {
   //dd("entra2");
        $id_campaign = Input::get('id_campaign');
        $receptores = Input::get('receptores');
        $message = Input::get('message');
        $header_mail = Input::get('header_mail');
        $subject = Input::get('subject');
    
       // $HeaderMailCampaign = ConfigHeaderModel::where('id',$id)->first();
		$mailcampaign = new MailCampaignModel();
		$mailcampaign->id_campaign= $id_campaign;
        $mailcampaign->receptores = $receptores;
        $mailcampaign->message = $message;
        $mailcampaign->header_mail = $header_mail;
        $mailcampaign->subject = $subject;
        $mailcampaign->save();
        // dd(json_decode($mailcampaign));

	  return $mailcampaign;
    }
}