<?php
namespace App\Containers\HilbeToday\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\HilbeToday\Tasks\MakeRequestTask;
use App\Containers\HilbeToday\Tasks\ManagePriceTask;
use App\Containers\HilbeToday\Tasks\ShowDollarPriceTask;


class HilbeTodayHandler extends Action {

  public static function getDollarPrice(){
    $price_data = (new MakeRequestTask())->run();
    $dollar = HilbeTodayHandler::storePrice($price_data);
    return json_encode($dollar);
  }
  public static function storePrice($price_object){
  	$dollar = (new ManagePriceTask())->run($price_object);
  	return $dollar;
  }
  public static function showDollarPrice(){
  	$dollar = (new ShowDollarPriceTask())->run();
  	return json_encode($dollar);
  } 
}