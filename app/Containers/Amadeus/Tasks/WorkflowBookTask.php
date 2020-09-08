<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result;
class WorkflowBookTask extends Task {
	public function run($cache,$client){
		$pricingResponse = (new PricePNR())->run(
			$client,
			$cache
		);
		if($pricingResponse->status === Result::STATUS_OK){
			if(is_array($pricingResponse->response->fareList)){
				$storedFare = (new StoreFare($cache->itinerary))->run(
					$pricingResponse->response->fareList,
					$client
				);
			}else{
				$storedFare = (new StoreFare($cache->itinerary))->run(
					$pricingResponse->response->fareList->fareReference->uniqueReference,
					$client
				);
			}
			if($storedFare->status === Result::STATUS_OK){
				$formOfPayment = (new CreateFormOfPayment())->run($client);
				return $formOfPayment;
			}else{
				return $storedFare;
			}
		}else{
			return $pricingResponse;
		}
	}
} 
