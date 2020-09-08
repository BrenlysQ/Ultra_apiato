<?php
namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Models\AlPoliciesModel;
use Illuminate\Support\Facades\Cache;
use App\Containers\Kiu\Models\FaresCacheModel;
class FareHandler extends Action{
  public static function GetFare($footprint, $payload){
    if($fare = FaresCacheModel::where([
        ['footprint','=',$footprint],
        ['currency','=',$payload->currency]
      ])->first()){
      return $fare;
    }else{
      return false;
    }
  }
}
