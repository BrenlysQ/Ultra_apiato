<?php

namespace App\Containers\UltraApi\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraApi\Actions\Banks\BanksHandler;
use App\Containers\UltraApi\Actions\Banks\Requests\StoreBankRequest;
use App\Containers\UltraApi\Actions\Banks\Requests\AssignBank;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Containers\User\Models\User;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use Illuminate\Http\Request;
class ControllerBanks extends ApiController
{
    public function FeeForm(Request $request){
      $feeid = $request->input('id',false);
      $users = User::role('client')->get()->tojson();
      $currencies = CurrenciesHandler::GetCurrList();
      $response = '{
        "users" : ' . $users . ',
        "currencies" : ' . $currencies;
      if($feeid){
        $response .= ',
          "fee" : ' . FeesHandler::GetFee($feeid);
      }
      $response .= '
        }';
      return $response;
    }
    public function ListBanks(){
      return BanksHandler::GetBanksList();
    }
    public function StoreBank(StoreBankRequest $request){
      return BanksHandler::StoreBank();
    }
    public function BankAssign(AssignBank $req){
      return BanksHandler::AssignBank();
    }
    public function BankDelete(){
      return BanksHandler::DeleteBank();
    }
    public function GetBank(){
      return BanksHandler::GetBank();
    }
}
