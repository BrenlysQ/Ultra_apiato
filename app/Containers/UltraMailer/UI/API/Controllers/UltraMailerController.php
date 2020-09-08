<?php

namespace App\Containers\UltraMailer\UI\API\Controllers;

use Illuminate\Http\Request;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraMailer\Actions\UltraMailerHandler;

class UltraMailerController extends ApiController
{
  
  	 public function sendMails()
  	{
    	return (new UltraMailerHandler())->manageRequest();
    }

  	 public function FullCampaignTable()
	{
		return (new UltraMailerHandler())->FullCampaignTable();
	}
   	public function FullStatusCampaign()
	{
		return (new UltraMailerHandler())->FullStatusCampaign();
	}
 	 public function FullConfigHeader()
	{
		return (new UltraMailerHandler())->FullConfigHeader();
	}

	 public function FullMailCampaign()
	{
		return (new UltraMailerHandler())->FullMailCampaign();
	}

	public function GetPeople()
	{	
		return (new UltraMailerHandler())->GetPeople();
	}
}
