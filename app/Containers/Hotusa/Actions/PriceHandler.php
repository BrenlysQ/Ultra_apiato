<?php
namespace App\Containers\Hotusa\Actions;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\Hotusa\Tasks\GetHotelFeeTask;
class PriceHandler{

  public static function PriceBuild($data,$currency = false){
    //$total = floatval($priceodo->TotalFare->Amount);
    $base = floatval($data);
    $fees = (new GetHotelFeeTask())->run();
    $totalfee = 0;
    //$totalfeepu = 0;
    /*foreach($fees->ownfees as $fee){
      //Si el tipo de fee es 1 quiere decir que es un Fee fijo
      // Por loq ue simplemente se suama el valor del fee al total
      if($fee->type == 1){
        $feetot = $fee->fee;
      //SI el tipo de fee es 2 es un fee porcentual
      //por lo que debemos hacer el calculo respectivo
      }else{
        $feetot = $base * ($fee->fee / 100);
      }
      $totalfee += $feetot;
    }*/

    foreach($fees as $fee){
      if($fee->type == 1){
        $feetot = $fee->fee;
      }else{
        $feetot = $base * ($fee->fee / 100);
      }
      $totalfee += $feetot;
    }
    $feepu = $feetot;
    $price['GlobalFare']['FeeAmount'] = round($feetot,2);
    $price['GlobalFare']['BaseInter'] = round($base,2);
    $price['GlobalFare']['BaseAmount'] = round($base + $feepu,2);
    $price['GlobalFare']['TotalAmount'] = round($base + $feepu);
    $price['GlobalFare']['CurrencyCode'] = $currency;
    return json_decode(json_encode($price));
  }

}
