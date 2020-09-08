<?php

namespace App\Containers\Kiu\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\UI\API\Requests\SearchFlight;
use App\Containers\Sabre\UI\Api\Requests\BookingRequest;
use App\Containers\Kiu\Actions\KiuHandler;
use App\Containers\Kiu\Actions\KiuNotifications;
use App\Containers\Kiu\Actions\KiuPolicies;
use App\Containers\Kiu\Actions\Requests\AvailabilityRequest;
use App\Containers\Kiu\Actions\Requests\PriceRequest;
use App\Commons\CommonActions;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Containers\Kiu\Actions\FlightsHandler;
use App\Containers\Kiu\Actions\KiuBook;

class ControllerKiu extends ApiController
{
	//BuildCache
  	function MakeCache () {

		$tomorrow = Carbon::now()->addDay();
		//print_r($tomorrow);
		$max_date=clone$tomorrow;
		//define la cantidad de meses que se guardaran en la cache
		//$max_date = $max_date->addMonths(1);
		$max_date = $max_date->addDays(7);
		//print_r($max_date);die;

		for ($departure_date=clone$tomorrow; $departure_date->diffInDays($max_date)>0; $departure_date->addDay()) {

			$x=clone$departure_date;
			$dd = $x->toDateString();
			$rd = $x->addDays(2);
			//print_r($dd);
			//print_r($rd->toDateString());
			//$rd=$departure_date->addDays(2);
			//$return_date=$return_date->toDateString();
			//print_r($rd);
			/*$payload = (object) array(
			  "trip" => Input::get('trip',1),
			  "departure_city" => Input::get('departure_city','CCS'),
			  "destination_city" => Input::get('destination_city','PTY'),
			  "departure_date" => $dd,
			  "return_date" => $rd->toDateString(),
			  "adult_count" => Input::get('adult_count',1),
			  "child_count" => Input::get('child_count',1),
			  "inf_count" => Input::get('inf_count',1),
			  "cabin" => Input::get('cabin','Economy'),
			  "currency" => Input::get('currency',1)
			);
			AvailabilityRequest::KIU_AirAvailRQ($payload);*/

		}

	}
  public function CreatePnr(BookingRequest $req){
	  //echo 'sssss'; die;
    return response()->json(KiuBook::Create());
  }
  public function KiuCache(){
    return KiuHandler::Cache();
  }
	public function GetFlights(SearchFlight $request) {
		return FlightsHandler::GetFlights();
	}
	public function StoreAirRoutes(){
      return KiuHandler::StoreKiuRoute();
    }
    public function getPolicies(){
      return KiuPolicies::getKiuPolicies();
    }
/*  function GetPrice () {
    PriceRequest::GetPrice();
  }*/
  public function checkLocalizables(){
    return KiuHandler::checkLocalizables();
  }

  public function demandTicket(Request $req){
    return KiuHandler::demandTicket($req);
  }

  public function checkProcess(){
      return KiuHandler::checkProcess();
	}
	
  public function checkNotifications(){
      return KiuNotifications::checkNotifications();
  }

}
