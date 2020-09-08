<?php
namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Models\AlPoliciesModel;
use Illuminate\Support\Facades\Cache;
class AlPoliciesHandler extends Action{
  public static function Get($params, $payload){
    if($parent = AlPoliciesHandler::GetParent($params, $payload)){
      return $parent;
    }else{
      return AlPoliciesHandler::GetSegment($params, $payload);
    }
  }
  private static function GetSegment($params, $payload){
    $identifier = $params->DepartureAirport->LocationCode .
                  '/' . $params->ArrivalAirport->LocationCode . '/' .
                  $params->MarketingAirline->CompanyShortName . '/' . $payload->passenger_type;
    $identifier = md5($identifier);
    $policies = Cache::remember('POL-' . $identifier, 1, function() use ($params, $payload)
    {
      return AlPoliciesModel::whereHas('route', function ($query) use ($params, $payload) {
        $query->whereRaw('(footprint = "' .
        $params->DepartureAirport->LocationCode . '/' . $params->ArrivalAirport->LocationCode
        . '" OR footprint = "'.
        $params->ArrivalAirport->LocationCode . '/' . $params->DepartureAirport->LocationCode
        .'") AND kiu_airline_policies.currency = ' . $payload->currency . ' AND airline = "' . $params->MarketingAirline->CompanyShortName . '"
        AND (passenger_type = "' . $payload->passenger_type . '" OR passenger_type = "*")');
      })->first();
    });
    if(!is_object($policies)){
      return false;
    }
    return $policies->classes;
    //echo $policies->classes; die;
  }
  private static function GetParent($params, $payload){
    $identifier = $payload->departure_city .
                  '/' . $payload->destination_city . '/' .
                  $params->MarketingAirline->CompanyShortName . '/' . $payload->passenger_type;
    $identifier = md5($identifier);
    $policies = Cache::remember('POL-' . $identifier, 1, function() use ($params, $payload)
    {
      return AlPoliciesModel::whereHas('route', function ($query) use ($params, $payload) {
        $query->whereRaw('(footprint = "' .
        $payload->departure_city . '/' . $payload->destination_city
        . '" OR footprint = "'.
        $payload->destination_city . '/' . $payload->departure_city
        .'") AND kiu_airline_policies.currency = ' . $payload->currency . ' AND airline = "' . $params->MarketingAirline->CompanyShortName . '"
        AND (passenger_type = "' . $payload->passenger_type . '" OR passenger_type = "*")
		');
      })->first();
    });
    if(!is_object($policies)){
      return false;
    }
    return $policies->classes;
  }
}
