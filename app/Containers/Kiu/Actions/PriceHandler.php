<?php
namespace App\Containers\Kiu\Actions;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
class PriceHandler{
  //Metodo para obtener los fees que aplixan para cada satelite
  public static function GetFeeByCurrencyCode($currency){
    $identifier = $currency.Auth::id();
    $fees = Cache::remember('FEE-' . $identifier, 1, function() use ($currency)
    {
      if($currency){
        return FeesHandler::GetFeeByCurrencyCode($currency);
      }
    });
    return $fees;
  }
  public static function PriceBuild($data){

    $priceodo = $data->fare;
    $acount = $data->payload->adult_count;
    //print_r($priceodo->airpricinginfo); die;
    $priceodo = $priceodo->airpricinginfo;
    $currency = $priceodo->TotalFare->CurrencyCode;
    $total = floatval($priceodo->TotalFare->Amount) * $acount;
    if($data->payload->currency == 4){
      $base = floatval($priceodo->BaseFare->Amount) * $acount;
    }else{
      $base = floatval($priceodo->EquivFare->Amount) * $acount;
    }
    $totaltax = $total - $base;
    $price['TotalFare'] = $priceodo->TotalFare;
    $price['Taxes'] = $priceodo->Taxes;
    $price['BaseFare'] = $priceodo->BaseFare;
    $fees = PriceHandler::GetFeeByCurrencyCode($currency);
    $totalfee = 0;
    $totalfeepu = 0;
    foreach($fees->ownfees as $fee){
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
    }
    foreach($fees->plusfees as $fee){
      if($fee->type == 1){
        $feetot = $fee->fee;
      }else{
        $feetot = $base * ($fee->fee / 100);
      }
      $totalfeepu += $feetot;
    }
    $currency = CurrenciesHandler::GetByCode($currency);
    $price['GlobalFare']['FeeAmount'] = $totalfee;
    $price['GlobalFare']['BaseInter'] = $base;
    $price['GlobalFare']['Base2Show'] = $base + $totalfeepu;
    $price['GlobalFare']['TotalTax'] = $totaltax;
    $price['GlobalFare']['BaseAmount'] = $total + $totalfeepu;
    $price['GlobalFare']['TotalAmount'] = $total + $totalfee + $totalfeepu;
    $price['GlobalFare']['CurrencyCode'] = $currency->code_visible;
    return json_decode(json_encode($price));
  }
}
?>
