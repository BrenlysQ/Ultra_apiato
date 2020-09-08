<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;  

class PayloadTask extends Task {
	public function run($departure_date,$return_date,$numberOfPassengers){
		$payload = CommonActions::CreateObject(); 
		$payload->currency = Input::get('currency');
		$payload->cabin = Input::get('cabin');
		$payload->departure_date = $departure_date;
		$payload->return_date = $return_date;
		$payload->passengers = $numberOfPassengers;
		$payload->trip     = Input::get('trip');
		$payload->departure_city = Input::get('departure_city');
		$payload->destination_city = Input::get('destination_city');
		$payload->child_count = Input::get('child_count');
		$payload->inf_count = Input::get('inf_count');
		$payload->adult_count = Input::get('adult_count'); 
		$payload->se = 13; 
		return $payload;
	}
}