<?php

namespace App\Containers\SabreHotel\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Sabre\Actions\SabreAuth;
use App\Containers\Sabre\Actions\RestClient;
use Illuminate\Support\Facades\Input;
use App\Containers\Sabre\Tasks\SoapRequestTask;
use App\Containers\SabreHotel\Tasks\HotelAvailabilityTask;
use App\Containers\SabreHotel\Tasks\HotelContentTask;
use App\Containers\SabreHotel\Tasks\HotelListTask;

class SabreActions extends Action{
  private $restclient;
  private $auth;
  private $token;
  public function __construct() {
    $this->auth = new SabreAuth();
    if($this->token = $this->auth->ValidateToken()){
        $this->restclient = new RestClient($this->token);
    }
  }
  public function getAvailability(){
    $request = (new HotelAvailabilityTask())->run();
    $response = (new SoapRequestTask())->run($request,'OTA_HotelAvailLLSRQ');
    return $response;
  }
  public function getContent(){
    $request = (new HotelContentTask())->run();
    $url = '/v1.0.0/shop/hotels/content?mode=content';
    $response = $this->restclient->executePostCall($url, $request);
    return $response;
  }
  public function getList(){
    $request = (new HotelListTask())->run();
    $response = (new SoapRequestTask())->run($request,'GetHotelListRQ');
    return $response;
  }
}