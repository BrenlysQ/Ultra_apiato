<?php
namespace App\Containers\UltraMailer\Actions;
use Mail;
use App\Ship\Parents\Actions\Action;
use App\Containers\UltraMailer\Models\UltraMailer;
use App\Containers\UltraMailer\Models\CampaignsModel;
use App\Containers\UltraMailer\Models\CampaignStatusModel;
use App\Containers\UltraMailer\Models\ConfigHeaderModel;
use App\Containers\UltraMailer\Models\MailCampaignModel;
use App\Containers\UltraMailer\Tasks\SendMailsTask;
use App\Containers\UltraMailer\Tasks\FullCampaignTable;
use App\Containers\UltraMailer\Tasks\FullStatusCampaign;
use App\Containers\UltraMailer\Tasks\FullConfigHeader;
use App\Containers\UltraMailer\Tasks\FullMailCampaign;
use App\Containers\UltraMailer\Tasks\GetPeople;

class UltraMailerHandler extends Action {

	public function manageRequest($id_campaign = false)
	{
			$mail =  (new SendMailsTask())->run();
	}

	public function FullCampaignTable()
	{
			//dd("entra");
	    	$mails = (new FullCampaignTable())->run();
	    	//dd("Alloooo bien2");
	    	return $mails;
	}
	public function FullStatusCampaign()
	{
			$status = (new FullStatusCampaign())->run();

			return $status;
	}

	public function FullConfigHeader()
	{
			$header = (new FullConfigHeader())->run();

			return $header;				
	}

	public function FullMailCampaign()
	{	
			//dd("entra");
			$mailcampaign = (new FullMailCampaign())->run();
			return $mailcampaign;
	}
	public function GetPeople()
	{	
			$mails = (new FullMailCampaign())->run();

			// dd($mails->receptores);
			$people = (new GetPeople())->run($mails->receptores);
			return $people;
	}
	/*
		public function FullStatusMail()
		{
			(new FullStatusCampaign())->run();
		}*/
	//}
}
