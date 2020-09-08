<?php

namespace App\Containers\UltraSMS\UI\API\Controllers;

use Illuminate\Http\Request;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraSMS\Actions\UltraSmsHandler;

class UltraSmsController extends ApiController
{
    public function sendSmsTask(){
      return (new UltraSmsHandler())->SmsRequest();
    }
    public function sendPufSms(){
      return (new UltraSmsHandler())->PufSms();
    }

    public function FullCampaingSms()
    {
      return (new UltraSmsHandler())->FullCampaingSms();
    }
}
