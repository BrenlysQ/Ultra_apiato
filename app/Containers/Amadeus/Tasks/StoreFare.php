<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\TicketCreateTstFromPricingOptions;
use Amadeus\Client\RequestOptions\Ticket\Pricing;
use App\Commons\CommonActions;

class StoreFare extends Task {
	public function __construct($itinerary){
		$this->itinerary = $itinerary;
	}
	public function run($tstNumber,$client){
		/*
			This task should receive the tstNumber from PricePNR Task
		*/
		$prices = StoreFare::buildingTST($tstNumber);
		$createTstResponse = $client->ticketCreateTSTFromPricing(
		    new TicketCreateTstFromPricingOptions([
		        'pricings' => $prices
		    ]) 
		);
		CommonActions::clientInteraction('ticketCreateTSTFromPricing',$client);
		if ($createTstResponse->status === Result::STATUS_OK) {
			return $createTstResponse;
		}else{
			$response = CommonActions::CreateObject();
			$response->status = 'Ha Ocurrido Un Problema (ticketCreateTSTFromPricing)';
			$response->messages = 'No se ha podido guardar la tarifa';
			$response->result = $createTstResponse->response;
			return $response;
		}
	}
	
	private function buildingTST($tstNumber){
		$prices = array();
		switch ($this->itinerary) {
			case (((int)$this->itinerary->adult_count > 0) && ((int)$this->itinerary->inf_count > 0) && ((int)$this->itinerary->child_count > 0)):
				if(is_array($tstNumber)){
					$prices[] = new Pricing([
					'tstNumber' => $tstNumber[0]->fareReference->uniqueReference,
					]);
					$prices[] = new Pricing([
						'tstNumber' => $tstNumber[1]->fareReference->uniqueReference,
					]);
					$prices[] = new Pricing([
						'tstNumber' => $tstNumber[2]->fareReference->uniqueReference
					]);
				}
				break;
			case (((int)$this->itinerary->adult_count > 0) && ((int)$this->itinerary->inf_count > 0)):
				if(is_array($tstNumber)){
					$prices[] = new Pricing([
					'tstNumber' => $tstNumber[0]->fareReference->uniqueReference,
					]);
					$prices[] = new Pricing([
						'tstNumber' => $tstNumber[1]->fareReference->uniqueReference
					]);
				}
				break;
			case (((int)$this->itinerary->adult_count > 0) && ((int)$this->itinerary->child_count > 0)):
				if(is_array($tstNumber)){
					$prices[] = new Pricing([
					'tstNumber' => $tstNumber[0]->fareReference->uniqueReference,
					]);
					$prices[] = new Pricing([
						'tstNumber' => $tstNumber[1]->fareReference->uniqueReference
					]);
				}
				break;
			case (((int)$this->itinerary->child_count > 0) && ((int)$this->itinerary->inf_count > 0)):
				if(is_array($tstNumber)){
					$prices[] = new Pricing([
					'tstNumber' => $tstNumber[0]->fareReference->uniqueReference,
					]);
					$prices[] = new Pricing([
						'tstNumber' => $tstNumber[1]->fareReference->uniqueReference
					]);
				}
				break;
			case ((int)$this->itinerary->adult_count > 0):
				$prices[] = new Pricing([
					'tstNumber' => $tstNumber
				]);
				break;
			case ((int)$this->itinerary->child_count > 0):
				$prices[] = new Pricing([
					'tstNumber' => $tstNumber
				]);
				break;
		}
		return $prices;
	}
}