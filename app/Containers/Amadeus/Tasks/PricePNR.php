<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\FarePricePnrWithBookingClassOptions;
use Amadeus\Client\RequestOptions\Fare\PricePnr\FareBasis;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use App\Commons\CommonActions;

class PricePNR extends Task {
	public $itinerary;
	public $fareTypes = array();
	public function run($client,$cache){
		PricePNR::getFareTypes($cache->flight->outbound);
		if($cache->itinerary->trip == 2)
			PricePNR::getFareTypes($cache->flight->return);
		if(is_array($this->fareTypes[0])){
			$fareTypes = $this->fareTypes[0];
		}else{
			$fareTypes = $this->fareTypes;
		}
		$pricingResponse = $client->farePricePnrWithBookingClass(
			new FarePricePnrWithBookingClassOptions([
				'overrideOptions' => $fareTypes
			])
		);
		CommonActions::clientInteraction('farePricePnrWithBookingClass',$client);
		if ($pricingResponse->status === Result::STATUS_OK) {
			return $pricingResponse;
		}else{ 
			$response = CommonActions::CreateObject();
			$response->status = 'Ha Ocurrido Un Problema (farePricePnrWithBookingClass)';
			$response->messages = 'La Tarifa ya no esta disponible';
			$response->result = $pricingResponse->response;
			return $response;
		}
	}
	public function getFareTypes($flight){
		foreach($flight->segments as $segment){
			if(is_array($segment->fareTypes)){
				foreach($segment->fareTypes as $fareType){
					if(!in_array($fareType,$this->fareTypes)){
						if($fareType == 'RV' && (!in_array('RU',$this->fareTypes))){
							$this->fareTypes[] = 'RU';
						}elseif($fareType != 'RV'){
							$this->fareTypes[] = $fareType;
						}
					}
				}
			}else{
				if(!in_array($segment->fareTypes,$this->fareTypes)){
					if($segment->fareTypes == 'RV' && (!in_array('RU',$this->fareTypes))){
						$this->fareTypes[] = 'RU';
					}elseif($segment->fareTypes != 'RV'){
						$this->fareTypes[] = $segment->fareTypes;
					}
				}
			}
		}
	}
} 