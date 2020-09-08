<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;

class SelectCurrencyTask extends Task {
	public function run($currency){
		if($currency == 3){
			$currency = 'VEF';
		}else{
			$currency = 'USD';
		}
		return $currency;
	}
}