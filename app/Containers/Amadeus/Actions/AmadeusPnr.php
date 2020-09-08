<?php
namespace App\Containers\Amadeus\Actions;
use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Actions\Action;
use App\Containers\Amadeus\Tasks\RequestModifyPnr;
use App\Commons\CommonActions;
class AmadeusPnr extends Action{
  public static function Create($data)
  {
	  $currency = CommonActions::currencyName(json_decode($data)->itinerary->currency);
	  $client = CommonActions::buildAmadeusClient($currency,false);
    $response = (new RequestModifyPnr())->run($data,$client);  
    return json_encode($response);
  }
}
?>
