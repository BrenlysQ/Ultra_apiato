<?php
namespace App\Containers\UltraSMS\Actions;
use Mail;
use App\Ship\Parents\Actions\Action;
use App\Mail\UltraMailerNotification;
use App\Containers\UltraSMS\Models\UltraSMS;
use App\Containers\UltraSMS\Models\SmsTypeModel;
use App\Containers\UltraSMS\Tasks\GetDataSmsTask;
use App\Containers\UltraSMS\Tasks\SendSmsTask;
use App\Containers\UltraSMS\Tasks\SendPufSmsTask;
use App\Containers\UltraSMS\Tasks\FullCampaingSms;

class UltraSmsHandler extends Action {

	public function SmsRequest(){
		$sms = (new SendSmsTask())->run();
		return $sms;
	}
	public function PufSms(){
		$sms = (new SendPufSmsTask())->run();
		return $sms;
	}
	public function SmsArtisanAll($msj){
		(new SendArtisanAllTask())->run($msj);
	}
	public function SmsApiAll(){
		(new SendApiAllTask())->run();
	}
	public function FullCampaingSms()
    {
      return (new FullCampaingSms())->run();
    }
}
