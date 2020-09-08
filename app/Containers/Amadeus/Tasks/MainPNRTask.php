<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use App\Commons\TagsGdsHandler;
use Illuminate\Support\Facades\Input;

class MainPNRTask extends Task {
	public function run(){
		$cache = (object) (new AmadeusCache())->run();
		$currency = CommonActions::currencyName($cache->itinerary->currency);
		//$InformativePricingResponse = (new InformativeBestPricingTask($cache))->run();
		$client = CommonActions::buildAmadeusClient($currency,false);
		$pnrCreated = (new AirSegmentFromRecommendations())->run($cache,$client);
		TagsGdsHandler::DestroyGdsTag(Input::get('tagpu'));
		return $pnrCreated; 
	}
}