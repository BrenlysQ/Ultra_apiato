<?php

namespace App\Containers\Sabre\Actions;
use App\Commons\TagsGdsHandler;
use Illuminate\Support\Facades\Cache;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Sabre\Tasks\CarAvailabilitySoapTask;
use App\Containers\Sabre\Tasks\CarLocationByAirportTask;
use App\Containers\Sabre\Tasks\SoapRequestTask;
use App\Containers\Sabre\Tasks\CarLocationTask;
use App\Containers\Sabre\Tasks\CarDetailsLocationTask;
use App\Containers\Sabre\Tasks\CarBookingTask;
use App\Containers\Sabre\Tasks\ParseCarAvailabilty;
class SabreCarsSoap extends Action{
	public function getAvailabilty(){
		$request = (new CarAvailabilitySoapTask())->run();
		//print_r($request); die;
		$response = (new SoapRequestTask())->run($request->xml,'OTA_VehAvailRateLLSRQ');
		//print_r($response); die;
		$avail = (new ParseCarAvailabilty())->run($response);
		//print_r($avail); die;
		$tagpu = SabreCarsSoap::cache($request->data,$avail);
		//print_r($req); die;
		//print_r($tagpu); die;
		$finalResponse = (object) array(
			'tagpu' => $tagpu,
			'avail' => $avail,
		);
		//print_r($finalResponse); die;

		return json_encode($finalResponse);
	}

	private static function cache($payload,$response){
	    $identifier = 'SABRECARS-' . str_random(25);
	    Cache::put($identifier, json_encode($response), 25);
	    return TagsGdsHandler::StoreCarTag($identifier, $payload);
	}

	public function getCarLocationByAirport(){
		$request = (new CarLocationByAirportTask())->run();
		$response = (new SoapRequestTask())->run($request,'VehLocationListLLSRQ');
	    return $response;
	}

	public function getCarLocation(){
		$request = (new CarLocationTask())->run();
		$response = (new SoapRequestTask())->run($request,'VehLocationFinderLLSRQ');
	    return $response;
	}

	public function getCarDetailsLocation(){
		$request = (new CarDetailsLocationTask())->run();
		$response = (new SoapRequestTask())->run($request,'OTA_VehLocDetailLLSRQ');
	    return $response;
	}

	public function getBooking(){
		$request = (new CarBookingTask())->run();
		$response = (new SoapRequestTask())->run($request,'OTA_VehResLLSRQ');
	    return $response;
	}
}
