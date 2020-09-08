<?php
namespace App\Containers\Satellite\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Satellite\Actions\SatelliteHandler;
use App\Containers\Configuration\Actions\SearchEngineHandler;
use App\Containers\Satellite\UI\API\Requests\StoreSatelliteRequest;
use App\Containers\Satellite\UI\API\Requests\AssignSatellite;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use Illuminate\Support\Facades\Config;
use App\Containers\User\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Api\Caller\ApiCall;
use Illuminate\Support\Facades\Auth;
use App\Commons\CommonActions;

class ControllerSatellites extends ApiController
{
    public function GetSatelliteList(){
      return SatelliteHandler::listSatellites();
    }
    public function GetSecret()
    {
      return SatelliteHandler::GetSecret();
    }
    public function StoreSatellite(StoreSatelliteRequest $request){
      return SatelliteHandler::CreateSatellite();
    }

    public function UpdateSatellite(Request $request){
      $id = $request->input('id',false);
      $gtSatellite = json_decode(SatelliteHandler::GetSatellite($id));
      return SatelliteHandler::updatingSatellite($gtSatellite);
    }

    public function UpdateSatellitePassword(Request $request){
      $id = $request->input('id',false);
      return SatelliteHandler::updatingSatellitePassword($id);
    }

    public function DeleteSatellite(){
      return SatelliteHandler::deleteSatellite();
    }

    public function GetDeletedSatelliteList(){
      return SatelliteHandler::listDeletedSatellites();
    }

    public function bundledSatellite(Request $request){
      $satelliteid = $request->input('id',false);
      $currencies = CurrenciesHandler::GetCurrList();
      $search_engines = SearchEngineHandler::listSearchEngines();
      $response = CommonActions::CreateObject();
      $response->currencies = json_decode($currencies);
      $response->search_engines = json_decode($search_engines);
      if($satelliteid){
        $response->satellite = json_decode(SatelliteHandler::GetSatellite($satelliteid));
      }
      return json_encode($response);
    }

	public function getSatelitesBalance(){
     return SatelliteHandler::getSatelitesBalance();
   }

   public function getSatelliteInvoices(){
     return SatelliteHandler::getSatelliteInvoices();
   }

}
