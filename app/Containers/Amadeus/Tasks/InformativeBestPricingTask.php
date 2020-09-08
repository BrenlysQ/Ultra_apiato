<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\RequestOptions\FareCheckRulesOptions;
use Amadeus\Client\RequestOptions\FareInformativeBestPricingWithoutPnrOptions;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Passenger;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\Segment;
use Amadeus\Client\RequestOptions\Fare\InformativePricing\PricingOptions;
use Amadeus\Client\RequestOptions\Fare\PricePnr\PaxSegRef;
use Amadeus\Client\Result;
use App\Commons\CommonActions;

class InformativeBestPricingTask extends Task {
	public $cache;
	public function __construct($cache){
		$this->cache = $cache;
	}
	public function run(){
		$currency = CommonActions::currencyName($this->cache->currency);
		$client = CommonActions::buildAmadeusClient($currency,false);
		$passengers = InformativeBestPricingTask::getPassengers();
		if($this->cache->flight->return){
			$segments = InformativeBestPricingTask::getSegments(
				$this->cache->flight->outbound->segments,
				$this->cache->flight->return->segments
			);
		}else{
			$segments = InformativeBestPricingTask::getSegments(
				$this->cache->flight->outbound->segments
			);
		}
		$opt = new FareInformativeBestPricingWithoutPnrOptions([
			'passengers' => $passengers,
			'segments' => $segments,
			'pricingOptions' => new PricingOptions([
				'overrideOptions' => [
					PricingOptions::OVERRIDE_FARETYPE_PUB,
					PricingOptions::OVERRIDE_FARETYPE_UNI
				],
				'currencyOverride' => $currency
			])
		]);
		$informativePricingResponse = $client->fareInformativeBestPricingWithoutPnr($opt);
		CommonActions::clientInteraction('fareInformativeBestPricingWithoutPnr',$client);
		if($informativePricingResponse->status === Result::STATUS_OK){
			//Este valor se repite tantas veces como tantos paxes haya
			//dd($informativePricingResponse->response->mainGroup->pricingGroupLevelGroup);
			//y varia si es ida y vuelta o solo ida, de un objeto a un array
			$opt = new FareCheckRulesOptions();
			//$opt->recommendations
			$rulesResponse = $client->fareCheckRules($opt);
			CommonActions::clientInteraction('fareCheckRules',$client);
			$logoutResponse = $client->securitySignOut();
			dd($rulesResponse);
		}else{
			dd($informativePricingResponse);
		}
	}
	private static function getClass($classbook){
		if(is_array($classbook)){
			$class = $classbook[0];
		}else{
			$class = $classbook;
		}
		return $class;
	}
	private static function getSegments($outboundSegments,$returnSegments = false){
		foreach($outboundSegments as $segment){
			$class = InformativeBestPricingTask::getClass($segment->classbook);
			$segments[] = new Segment([
				'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', $segment->dtime, new \DateTimeZone('UTC')),
				'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', $segment->atime, new \DateTimeZone('UTC')),
				'from' => $segment->origin->IATA,
				'to' => $segment->destination->IATA,
				'marketingCompany' => $segment->airline->iata,
				'flightNumber' => $segment->flightno,
				'bookingClass' => $class,
				'groupNumber' => 1
			]);
		}
		if($returnSegments){
			foreach($returnSegments as $segment){
				$class = InformativeBestPricingTask::getClass($segment->classbook);
				$segments[] = new Segment([
					'departureDate' => \DateTime::createFromFormat('Y-m-d H:i:s', $segment->dtime, new \DateTimeZone('UTC')),
					'arrivalDate' => \DateTime::createFromFormat('Y-m-d H:i:s', $segment->atime, new \DateTimeZone('UTC')),
					'from' => $segment->origin->IATA,
					'to' => $segment->destination->IATA,
					'marketingCompany' => $segment->airline->iata,
					'flightNumber' => $segment->flightno,
					'bookingClass' => $class,
					'groupNumber' => 1
				]);
			}
		}
		return $segments;
	}
	private function getPassengers(){
		//dd($this->cache->itinerary);
		if((int)$this->cache->itinerary->adult_count > 0){
			$passengers[] = new Passenger ([			
				'type' => Passenger::TYPE_ADULT,
				'tattoos' => [1]
			]);
		}
		if((int)$this->cache->itinerary->child_count > 0){
			$passengers[] = new Passenger ([			
				'type' => Passenger::TYPE_CHILD
			]);
		}
		if((int)$this->cache->itinerary->inf_count > 0){
			$passengers[] = new Passenger ([			
				'type' => Passenger::TYPE_INFANT
			]);
		}
		return $passengers;
	}
}