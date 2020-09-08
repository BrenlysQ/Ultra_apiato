<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Fees\FeesHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;

class PriceBuilderTask extends Task {
	public $recommendation;
	public function __construct($recommendation){
		$this->recommendation = $recommendation;
	}
	public function run($priceodo){
		$price = PriceBuilderTask::PriceBuild($priceodo);
		return $price;
	}
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
	public function PriceBuild($priceodo){
	    if(Input::get('currency') ==3){
			$currency = 'VEF';
		}else{
			$currency = 'USD';
		}
	    $total = $priceodo->monetaryDetail[0]->amount;
	    $totaltax = $priceodo->monetaryDetail[1]->amount;
	    $fees = PriceBuilderTask::GetFeeByCurrencyCode($currency);
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
			$price['GlobalFare']['Recommendation'] = $this->recommendation;
	    return $price;
	}
}