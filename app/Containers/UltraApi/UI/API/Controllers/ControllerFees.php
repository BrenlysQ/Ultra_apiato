<?php

namespace App\Containers\UltraApi\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use App\Containers\UltraApi\Actions\Fees\Requests\StoreFeeRequest;
use App\Containers\UltraApi\Actions\Currencies\Requests\AsgCurrReq;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ControllerFees extends ApiController
{

    public function FeeList(){
      return FeesHandler::ListFee();
    }
    public function AssignCurrency(AsgCurrReq $req){
      return CurrenciesHandler::CurrencyAssign();
    }
    public function GetFee(){
      return FeesHandler::GetFee();
    }
    public function DeleteFee(){
      return FeesHandler::FeeDelete();
    }
    public function StoreFee(StoreFeeRequest $req){
      return FeesHandler::StoreFee();
    }
}
