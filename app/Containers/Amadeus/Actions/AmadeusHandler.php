<?php

namespace App\Containers\Amadeus\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Log;
use App\Containers\Amadeus\Tasks\MasterPricerTravelBoard;
use App\Containers\Amadeus\Tasks\MainPNRTask;
use App\Containers\Amadeus\Tasks\AmadeusCache;
use App\Containers\Amadeus\Tasks\AirSegmentFromRecommendations;
use App\Containers\Amadeus\Tasks\CreatePNR;
use App\Containers\Amadeus\Tasks\PricePNR;
use App\Containers\Amadeus\Tasks\StoreFare;
use App\Containers\Amadeus\Tasks\CreateFormOfPayment;
use App\Containers\Amadeus\Tasks\SavePNR;
use App\Containers\Amadeus\Tasks\RetrievePNRTask;
use App\Containers\Amadeus\Tasks\MasterPricerCalendar;
use App\Containers\Amadeus\Tasks\TicketIssueTask;
class AmadeusHandler extends Action{
  public function GetFlights(){
    $response = $this->call(MasterPricerTravelBoard::class,[]);
	 return $response;
  }
  public function CreatePNR(){
    $response = $this->call(MainPNRTask::class,[]);
	 return $response;
  }
  public function RetrievePNR(){
    $response = $this->call(RetrievePNRTask::class,[]);
	 return $response;
  }
  
  public function GetCalendar(){
    $response = $this->call(MasterPricerCalendar::class,[]);
    return $response;
  }
  
  public function IssueTicket(){
    return json_encode($this->call(TicketIssueTask::class,[]));
  }
  
  /*public function BookFlights(){
    $response = $this->call(AirSegmentFromRecommendations::class,[]);
	 return $response;
  }
  public function CreatePNR(){
  	$response = $this->call(CreatePNR::class,[]);
	 return $response;
  }
  public function PricePNR(){
  	$response = $this->call(PricePNR::class,[]);
  	return $response;
  }
  public function StoreFare(){
  	$response = $this->call(StoreFare::class,[]);
    return $response;
  }
  public function CreateFOP(){
  	$response = $this->call(CreateFormOfPayment::class,[]);
    return $response;
  }
  public function SavePNR(){
  	$response = $this->call(SavePNR::class,[]);
    return $response;
  }*/
  public function AmadeusCache(){
    $response = $this->call(AmadeusCache::class,[]);
    return $response;
  }
}
