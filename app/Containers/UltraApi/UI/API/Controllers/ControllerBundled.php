<?php

namespace App\Containers\UltraApi\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraApi\Actions\Banks\BanksHandler;
use App\Commons\TagsGdsHandler;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Containers\User\Models\User;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use App\Containers\Satellite\Actions\SatelliteHandler;
use App\Containers\Payments\Actions\PgatewayHandler;
use App\Containers\Satellite\Models\API_satellite;
use Illuminate\Http\Request;
use App\Containers\UltraApi\Actions\Routes\RoutesHandler;
use App\Containers\UltraApi\UI\API\Requests\StoreRouteRequest;
use App\Commons\CommonActions;
use App\Containers\Sabre\Actions\BargainFinderMax;
use App\Containers\Kiu\Actions\KiuHandler;
class ControllerBundled extends ApiController
{
    public function FeeForm(Request $request){
      $feeid = $request->input('id',false);
      $currencies = CurrenciesHandler::GetCurrList();
      $users = User::with('satellite')->get()->tojson();
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
    public function GetRoute(){
      return RoutesHandler::GetRoute();
    }
    public function DeleteRoute(){
      RoutesHandler::RouteDelete();
    }
    public function RouteStore(StoreRouteRequest $request){
      RoutesHandler::store($request);
    }
    public function ListRoutes(){
      return RoutesHandler::ListRoutes();
    }
    //FUNCTION TO RESPONSE ITINERARY AND BANKS BY CURRENCY
    public function BookingView(){
      $res = TagsGdsHandler::GetGdsTag();
      $itinerary = json_decode((TagsGdsHandler::GetGdsTag())->itinerary);
      $gateways = CurrenciesHandler::GetPgateWays($itinerary);
      return json_encode((object) array(
        "itinerary" => $itinerary,
        "pgateways" => PgatewayHandler::GetTabs($gateways,$itinerary)
      ));
    }
	 public function BookingView2(Request $request){
		//$tagpu = $request->input('tagpu');
		$selector = $request->input('tagpu');
		$data = explode('-', $selector);
        $book_data = CommonActions::CreateObject();
        foreach($data as $key => $dat){
          if($key == 0){
            $book_data->tag = $dat;
          }else {
            $book_data->legs[] = (object)array(
              "seqnumber" => $dat,
              "type" => $key
            );
          }
        }
      $res = TagsGdsHandler::GetGdsTag($book_data->tag);
      $itinerary = json_decode($res->itinerary);
          //dd($itinerary);
      $gateways = CurrenciesHandler::GetPgateWays($itinerary);
          if($itinerary->se == 1){
                $sabre = new BargainFinderMax();
                $item = $sabre->BFMCache($book_data->legs, $book_data->tag);
          }else{
                $item = KiuHandler::Cache(true, $book_data->tag, $book_data->legs);
          }
          //dd($item);
      return json_encode((object) array(
        "itinerary" => $itinerary,
        "item" => $item,
                "selector" => $selector,
                "tagpu" => $book_data->tag
      ));
    }

}
