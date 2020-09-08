<?php
namespace App\Containers\Sabre\Commons;
use App\Commons\TagsGdsHandler;
use App\Commons\CommonActions;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\Sabre\Actions\SabreAuth;
use App\Containers\Sabre\Actions\RestClient;
use Illuminate\Support\Facades\Cache;
use DateTime;
class SabreCommons extends CommonActions{
  public static function GetCache($tagpu = false){
    if($tagid = TagsGdsHandler::GetGdsTag($tagpu)){
      $cache = SabreCommons::CallCacheSabre($tagid->tag_id);
      if(property_exists($cache,'errorCode')){
        return SabreCommons::ErrorCache();
      }
      return $cache;
    }else{
      return SabreCommons::ErrorCache();
    }
  }
  public static function RequestPayloadPax($data,$json = true){
    $paxes = '';
    $paxes .= SabreCommons::RequestPaxesOne($data->adult_count,"ADT",$json);
    $paxes .= SabreCommons::RequestPaxesOne($data->child_count,"C07",$json);
    $paxes .= SabreCommons::RequestPaxesOne($data->inf_count,"INF",$json);
    if($json){
      $paxes = substr($paxes, 0, -1);
    }
    return $paxes;
  }
  private static function RequestPaxesOne($count,$type,$json = true){
    if($count > 0){
      if($json){
        return '{
                    "Code": "' . $type . '",
                    "Quantity": ' . $count . '
                },';
      }else{
        return '<PassengerTypeQuantity Code="' . $type . '" Quantity="' . $count . '" />';
      }
    }
    return '';
  }
  private static function CallCacheSabre($tag_id){
    $url = '/v3.0.0/shop/flights/tags/' . $tag_id . '?mode=live';
    $auth = new SabreAuth();
    $token = $auth->ValidateToken();
    $restclient = new RestClient($token);
    return json_decode($restclient->executeGetCall($url));
  }
  private static function BFMCache($legs,$tagid,$time_left){
    $itinerary = '';
    foreach($legs as $leg){
      $cache = SabreCommons::CallCacheSabre($tagid->tag_id . '~' . $leg->seqnumber);
      if(property_exists($cache,'errorCode')){
        return SabreCommons::ErrorCache();
      }else{
        $lega = $cache->AirItinerary->OriginDestinationOptions->OriginDestinationOption[$leg->type - 1];
        if(empty($itinerary)){
          $itinerary = $cache;
          $itinerary->AirItinerary->OriginDestinationOptions->OriginDestinationOption = array();
        }

        $itinerary->AirItinerary->OriginDestinationOptions->OriginDestinationOption[] = $lega;
      }
    }
    $itinstored = json_decode($tagid->itinerary);
    return (object) array(
      'itinerary' => $itinerary,
      'currency' => CurrenciesHandler::GetCurrency($itinstored->currency),
      'departure_date' => $itinstored->departure_date,
      'itinstored' => $itinstored,
      'time_left' => $time_left
    );
  }
  public static function TrheeDays($legs,$tagid,$time_left){
      $itinerary = '';
    foreach($legs as $leg){
      if(!$cache = json_decode(Cache::get($tagid->tag_id))){
        return SabreCommons::ErrorCache();
      }else{
        $cache = $cache->Body->OTA_AirLowFareSearchRS->PricedItineraries->PricedItinerary[$leg->seqnumber - 1];
        //print_r($cache); die;
        $lega = $cache->AirItinerary->OriginDestinationOptions->OriginDestinationOption[$leg->type - 1];
        if(empty($itinerary)){
          $itinerary = $cache;
          $itinerary->AirItinerary->OriginDestinationOptions->OriginDestinationOption = array();
        }

        $itinerary->AirItinerary->OriginDestinationOptions->OriginDestinationOption[] = $lega;
      }
    }
    $itinstored = json_decode($tagid->itinerary);
    return (object) array(
      'itinerary' => $itinerary,
      'currency' => CurrenciesHandler::GetCurrency($itinstored->currency),
      'departure_date' => $itinstored->departure_date,
      'itinstored' => $itinstored,
      'time_left' => $time_left
    );
  }
  public static function IntineraryCache($legs, $tagpu = false){

    if($tagid = TagsGdsHandler::GetGdsTag($tagpu)){

      $time_left = CommonActions::TimeLeft($tagid->gen_date);
      $itinstored = json_decode($tagid->itinerary);
      //print_r($itinstored); die;
      if($itinstored->se == 1){
        return SabreCommons::BFMCache($legs,$tagid,$time_left);
      }else{
        return SabreCommons::TrheeDays($legs,$tagid,$time_left);
      }
    }else{
      return SabreCommons::ErrorCache();
    }
  }
}
?>
