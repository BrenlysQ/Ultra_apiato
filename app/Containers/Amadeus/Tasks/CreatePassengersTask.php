<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\RequestOptions\FareMasterPricerTbSearch;
use Amadeus\Client\RequestOptions\Fare\MPPassenger;
use Illuminate\Support\Facades\Input;  

class CreatePassengersTask extends Task {
	public function run(){
		if((Input::get('adult_count') >= 1 ) && (Input::get('child_count') == 0) && (Input::get('inf_count') == 0)){
			$passengers = [
				new MPPassenger([
					'type' => MPPassenger::TYPE_ADULT,
					'count' => Input::get('adult_count')
				])
			];
		}elseif((Input::get('adult_count') == 0 ) && (Input::get('child_count') >= 1) && (Input::get('inf_count') == 0)){
			$passengers = [
				new MPPassenger([
					'type' => MPPassenger::TYPE_CHILD,
					'count' => Input::get('child_count')
				])
			];  
		}elseif((Input::get('adult_count') >= 1) && (Input::get('child_count') >= 1) && (Input::get('inf_count') == 0)){
			$passengers = [
				new MPPassenger([
					'type' => MPPassenger::TYPE_ADULT,
					'count' => Input::get('adult_count')
				]),
				new MPPassenger([
					'type' => MPPassenger::TYPE_CHILD,
					'count' => Input::get('child_count')
				])
			];  
		}elseif((Input::get('adult_count') >= 1) && (Input::get('child_count') >= 1) && (Input::get('inf_count') >= 1)){
			$passengers = [
				new MPPassenger([
					'type' => MPPassenger::TYPE_ADULT,
					'count' => Input::get('adult_count')
				]),
				new MPPassenger([
					'type' => MPPassenger::TYPE_CHILD,
					'count' => Input::get('child_count')
				]),
				new MPPassenger([
					'type' => MPPassenger::TYPE_INFANT,
					'count' => Input::get('inf_count')
				]),
			]; 
		}elseif((Input::get('adult_count') >= 1) && (Input::get('inf_count') >= 1)){
			$passengers = [
				new MPPassenger([
					'type' => MPPassenger::TYPE_ADULT,
					'count' => Input::get('adult_count')
				]),
				new MPPassenger([
					'type' => MPPassenger::TYPE_INFANT,
					'count' => Input::get('inf_count')
				])
			]; 
		}  		
		return $passengers;   
	}
}