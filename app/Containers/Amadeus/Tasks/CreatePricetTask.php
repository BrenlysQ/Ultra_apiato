<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use App\Containers\Amadeus\Tasks\PriceBuilderTask;

class CreatePricetTask extends Task {
	public function run($option,$recommendation){
	  	$price = (new PriceBuilderTask($recommendation))->run($option->recPriceInfo);
	  	return $price;
	}
}