<?php

namespace App\Containers\UltraApi\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraApi\Actions\Users\UsersHandler;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Containers\UltraApi\Actions\TokenSatellite;

class ControllerUsers extends ApiController
{
    public function SaveUserFee(){
      return UsersHandler::SaveFee();
    }
    public function GetFee(){
      return UsersHandler::GetFee();
    }
    public function BankAssign(){
      return UsersHandler::AssignBank();
    }
    public function GetBanksList(){
      return UsersHandler::GetBanksList();
    }
    public function GetFeeList(){
      return UsersHandler::GetUserFeeList();
    }
    public function GetSatelliteToken(){
      return $this->call(TokenSatellite::class);
    }
}
