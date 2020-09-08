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

class UltraMailerComplete extends Action {

	public function Massive()
	{
			$header = (new FullConfigHeader())->run();
			$mailcampaign = (new FullMailCampaign())->run();
	    	$status = (new FullStatusCampaign())->run();
	    	$mails = (new FullCampaignTable())->run();
	    	 // dd($mails);
	    	$recept = (new GetPeople())->run($mails->receptores);
	    	//dd($recept);
	    	$send = (new SendMailsTask())->run($recept);
		
		return $send;
	}
}