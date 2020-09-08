<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;

class CabinOptionTask extends Task {
	public function run($cabinType){
		switch ($cabinType) {
			case 'Economy':
				$cabin = FareMasterPricerTbSearch::CABIN_ECONOMY;
				$option = FareMasterPricerTbSearch::CABINOPT_MANDATORY;
				break;
			case 'Premiumeconomy':
				$cabin = FareMasterPricerTbSearch::CABIN_ECONOMY_PREMIUM;
				$option = FareMasterPricerTbSearch::CABINOPT_MANDATORY;
				break;
			case 'Business':
				$cabin = FareMasterPricerTbSearch::CABIN_BUSINESS;
				$option = FareMasterPricerTbSearch::CABINOPT_MANDATORY;
				break;
			case 'First':
				$cabin = FareMasterPricerTbSearch::CABIN_FIRST_SUPERSONIC;
				$option = FareMasterPricerTbSearch::CABINOPT_MANDATORY;
				break;
			case 'Nopreference':
				$cabin = null;
				$option = null;
				break;	
		}
		$response = CommonActions::CreateObject();
		$response->cabin = $cabin;
		$response->option = $option;
		return $response;
	}
}