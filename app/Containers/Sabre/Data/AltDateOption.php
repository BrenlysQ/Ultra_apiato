<?php

namespace App\Containers\Sabre\Data;
use App\Containers\Kiu\Data\Segment;
use Carbon\Carbon;
use App\Containers\Sabre\Data\OdoStructure;
use App\Containers\Kiu\Actions\AlPoliciesHandler;
use App\Commons\Airports;
use App\Commons\CommonActions;
use App\Commons\Airline;
use App\Containers\UltraApi\Actions\Prices\PriceHandler;
class AltDateOption
{
	public $price;
	public $departure_date;
	public $return_date;
	public $odo;
	public function __construct($response,$departure_date,$return_date,$payload){
		$this->return_date = $return_date;
		$this->departure_date = $departure_date;
		if($response){
			$price = $response->AirItineraryPricingInfo;
			$this->price = PriceHandler::PriceBuild($price); //COntruccion del precio a mostrar en el calendario
			$this->odo = new OdoStructure($response,$payload); //COnstruccion del odo a mostrar al momento de seleccionar en el calendario
		}
	}
}
