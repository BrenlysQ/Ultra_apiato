<?php

namespace App\Containers\UltraApi\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\UltraApi\Actions\Currencies\Requests\StoreCurrRequest;
use App\Containers\UltraApi\Actions\Currencies\Requests\AsgCurrReq;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ControllerCurrencies extends ApiController
{

    public function ListCurrencies(){
      return CurrenciesHandler::GetCurrList();
    }
    public function AssignCurrency(AsgCurrReq $req){
      return CurrenciesHandler::CurrencyAssign();
    }
    public function GetCurrency(){
      return CurrenciesHandler::GetCurrency();
    }
    public function DeleteCurrency(){
      return CurrenciesHandler::DeleteCurr();
    }
    public function StoreCurr(StoreCurrRequest $req){
      return CurrenciesHandler::StoreCurr();
    }
}
