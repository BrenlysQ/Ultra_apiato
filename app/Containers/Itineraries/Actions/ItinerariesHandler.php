<?php

namespace App\Containers\Itineraries\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Tasks\RemindSendPnrTask;
use App\Containers\Itineraries\Tasks\UpdatePnrTask;
use App\Containers\Itineraries\Tasks\CreateOfficeItineraryTask;
use App\Containers\Itineraries\Tasks\CheckOfficeItinerariesTask;
use App\Containers\Itineraries\Tasks\GetOfficeUserItinerariesTask;
use App\Containers\Itineraries\Tasks\GetOfficeItinerariesTask;
use App\Containers\Itineraries\Tasks\CreateAirlineBalanceTask;
use App\Containers\Itineraries\Tasks\UpdateAirlineBalanceTask;
use App\Containers\Itineraries\Tasks\VerifyKiuItineries;
use App\Containers\Kiu\Actions\KiuPnr;
use App\Containers\Amadeus\Actions\AmadeusPnr;
use App\Containers\Sabre\Actions\SabrePnr;

class ItinerariesHandler extends Action {

  public function remindSendPnr(){
    $response = $this->call(RemindSendPnrTask::class,[]);
    return $response;
  }
  public function updatePnr($req){
    $response = $this->call(UpdatePnrTask::class,[$req]);
    switch ($response->itinerary->se) {
      case 1:
        return response()->json(SabrePnr::Create($response));
        break;
      case 2:
        return response()->json(KiuPnr::Create($response));
        break;
      case 13:
        return (AmadeusPnr::Create($response));
        break;
      default:
        return $response;
        break;
    }
    return $response;
  }
  public function createOfficeItinerary($req){
    $response = $this->call(CreateOfficeItineraryTask::class,[$req]);
    return $response;
  }
  public function checkOfficeItineraries(){
    $itineraries = OfficeItineraryModel::where('status',0)
                          ->orWhere('status', 1)->get();
    foreach ($itineraries as $itinerary) {
      switch ($itinerary->itinerary->se){
        case 1:
          return 'envialo a Sabre task';
          break;
        case 2:
          return $this->call(CheckOfficeItinerariesTask::class,[$itinerary]);
        break;
        case 13:
          return 'amadeus';
      }
    }
  }
  public function getOfficeUserItineraries($req){
    $response = $this->call(GetOfficeUserItinerariesTask::class,[$req]);
    return $response;
  }
  public function getOfficeItineraries($req){
    $response = $this->call(GetOfficeItinerariesTask::class,[$req]);
    return $response;
  }
  public function createAirlineBalance($req){
    $response = $this->call(CreateAirlineBalanceTask::class,[$req]);
    return $response;
  }
  public function updateAirlineBalance($req){
    $response = $this->call(UpdateAirlineBalanceTask::class,[$req]);
    return $response;
  }
}
