<?php
namespace App\Containers\Sabre\Actions; 
use App\Commons\CommonActions; 
use App\Commons\TagsGdsHandler; 
use App\Ship\Parents\Actions\Action; 
use App\Containers\UltraApi\Actions\Users\UsersHandler; 
use App\Containers\Sabre\Commons\SabreCommons; 
use Illuminate\Support\Facades\Input; 

use Illuminate\Support\Facades\Log; 
use App\Containers\Sabre\Tasks\BfmPayloadTask; 
class BargainFinderMax extends Action{ 
  private $restclient; 
  private $auth; 
  private $token; 
  public function __construct() { 
    $this->auth = new SabreAuth(); 
    if($this->token = $this->auth->ValidateToken()){ 
      $this->restclient = new RestClient($this->token); 
    } 
  }
  public function BFMCache(){ 
    set_time_limit(0); 
    $legs = json_decode(Input::get('legs')); 
    //print_r($legs); 
    $data = SabreCommons::IntineraryCache($legs); 
    //print_r($data); die; 
    if(property_exists($data,'error')){ 
      return $data; 
    }else{ 
      $travel = new TravelOption($data->itinerary, $data->itinstored); 
      $flight = (object) array( 
        "outbound" => $travel->outboundflight, 
        "return" => $travel->returnflight, 
        "price" => $travel->price, 
      ); 
      $banks = UsersHandler::GetBanksList($data->currency); 
      $json = array( 
        "flight" => $flight, 
        "banks" => $banks, 
        "time_left" => $data->time_left 
        ); 
      return $json; 
    }
  }
  public function GetFlights($cache = false){ 
    set_time_limit(0); 
    if(!$this->token && !$cache){ 
      return array('error'=>'Api error AUTH'); 
    } 
    $url = '/v3.0.0/shop/flights?mode=live&enabletagging=true'; 
    $payload = (object) array( 
      "trip" => Input::get('trip',1), 
      "departure_city" => Input::get('departure_city','CCS'), 
      "destination_city" => Input::get('destination_city','PTY'), 
      "departure_date" => CommonActions::FormatDate(Input::get('departure_date','2017-05-16')), 
      "return_date" => CommonActions::FormatDate(Input::get('return_date','2017-05-16')), 
      "adult_count" => Input::get('adult_count',1), 
      "child_count" => Input::get('child_count',0), 
      "inf_count" => Input::get('inf_count',0), 
      "cabin" => Input::get('cabin','Y'), 
      "currency" => Input::get('currency',1), 
      "se" => 1 
    );
     $filecache = app_path('Containers/UltraApi/Data/Cache/BFMCache.pic'); 
    if(!$cache){ 
      $request = (new BfmPayloadTask())->run($payload); 
      $data = $this->restclient->executePostCall($url, $request); 
      //dd($data); 
      }else{ 
      $data = file_get_contents($filecache);  
    }
    return $this->ParseResponse(json_decode($data),$payload); 
  }

  private function ParseResponse($data,$payload){ 
    if(!property_exists($data, 'errorCode')){ 
      if(property_exists($data, 'OTA_AirLowFareSearchRS') && property_exists($data->OTA_AirLowFareSearchRS,'PricedItineraries')){ 
        $itineraries = $data->OTA_AirLowFareSearchRS->PricedItineraries->PricedItinerary; 
        $tosrted = CommonActions::CreateObject(); 
        $tosrted->payload = $payload; 
        $tosrted->tagpu = TagsGdsHandler::StoreTag($data->RequestID , $payload); 
        $result = new SortedResults($itineraries, $tosrted); 
        return $result; 
      }else{ 
        $response['errorCode'] = 200; 
        $response['debug'] = $data; 
        return $response; 
      } 
    }else{
      $response['errorCode'] = $data->errorCode; 
      $response['GSDERROR'] = $data; 
      return $response; 
    }
    } 
} 
?> 