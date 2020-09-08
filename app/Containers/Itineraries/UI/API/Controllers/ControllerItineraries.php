<?php

namespace App\Containers\Itineraries\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Itineraries\Actions\ItinerariesHandler;
use Illuminate\Http\Request;

class ControllerItineraries extends ApiController
{
  public $freelance;
  public function __construct(){
    $this->itineraries = new ItinerariesHandler();
  }
  public function remindSendPnr(){
    return $this->itineraries->remindSendPnr();
  }
  public function updatePnr(Request $req){
    return $this->itineraries->updatePnr($req);
  }
  public function createOfficeItinerary(Request $req){
    return $this->itineraries->createOfficeItinerary($req);
  }
  public function checkOfficeItineraries(){
    return $this->itineraries->checkOfficeItineraries();
  }
  public function getOfficeUserItineraries(Request $req){
    return $this->itineraries->getOfficeUserItineraries($req);
  }
  public function getOfficeItineraries(Request $req){
    return $this->itineraries->getOfficeItineraries($req);
  }
  public function createAirlineBalance(Request $req){
    return $this->itineraries->createAirlineBalance($req);
  }
  public function updateAirlineBalance(Request $req){
    return $this->itineraries->updateAirlineBalance($req);
  }

}
