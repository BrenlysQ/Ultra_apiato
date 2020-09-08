<?php

namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Sabre\Tasks\CarAvailabilityTask;

class SabreCars extends Action{
	private $restclient;
  	private $auth;
  	private $token;
  	public function __construct() {
    	$this->auth = new SabreAuth();
    	if($this->token = $this->auth->ValidateToken()){
      		$this->restclient = new RestClient($this->token);
    	}
  	}
	public function getAvailabilty(){
		set_time_limit(0);
	    $url = '/v2.4.0/shop/cars';
	    $request = (new CarAvailabilityTask())->run();
	    $data = $this->restclient->executePostCall($url, $request);
	    return $data;
	}
}