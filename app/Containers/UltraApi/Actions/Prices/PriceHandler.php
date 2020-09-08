<?php
namespace App\Containers\UltraApi\Actions\Prices;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;

class PriceHandler{
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
  public static function PriceBuild($priceodo){
    $currency = $priceodo->ItinTotalFare->TotalFare->CurrencyCode;
    $total = $priceodo->ItinTotalFare->TotalFare->Amount;
    //dd("fjfhfhfh");
    (is_array($priceodo->ItinTotalFare->Taxes->Tax)) ? $totaltax = $priceodo->ItinTotalFare->Taxes->Tax[0]->Amount : $totaltax = $priceodo->ItinTotalFare->Taxes->Tax->Amount;

    $price['TotalFare'] = $priceodo->ItinTotalFare->TotalFare;
    $price['Taxes'] = $priceodo->ItinTotalFare->Taxes;
    $price['BaseFare'] = $priceodo->ItinTotalFare->BaseFare;
    $fees = PriceHandler::GetFeeByCurrencyCode($currency);
    //dd($fees);
    $total = floatval($total);
    $totaltax = floatval($totaltax);
    $base =  $total - $totaltax;
    $totalfee = 0;
    $totalfeepu = 0;
    foreach($fees->ownfees as $fee){
      if($fee->type == 1){
        $feetot = $fee->fee;
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
    $price['GlobalFare']['TotalTax'] = $totaltax;
    $price['GlobalFare']['Base2Show'] = $base + $totalfeepu;
    $price['GlobalFare']['BaseAmount'] = $total + $totalfeepu;
    $price['GlobalFare']['TotalAmount'] = $total + $totalfee + $totalfeepu;
    $price['GlobalFare']['CurrencyCode'] = $currency->code_visible;
	$price['GlobalFare']['FEEPL'] = $fees->plusfees;
    return json_decode(json_encode($price));
  }
}
?>
