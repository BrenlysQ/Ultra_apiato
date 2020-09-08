<?php

namespace App\Containers\Sabre\UI\API\Controllers;
use App\Commons\TagsGdsHandler;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Sabre\Actions\SabreActions;
use App\Containers\Sabre\Actions\SabreBook;
use App\Containers\Sabre\Actions\AlternateDays;
use App\Containers\Sabre\Actions\SabrePolicies;
use App\Containers\Sabre\Actions\SabreCars;
use App\Containers\Sabre\Actions\SabreCarsSoap;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Containers\Sabre\UI\API\Requests\BookingCacheRequest;
use App\Containers\Sabre\UI\API\Requests\BookingRequest;
use App\Containers\Sabre\UI\API\Requests\BargainFinderMaxRequest;
use Apiato\Core\Foundation\Facades\Apiato;

class ControllerSabre extends ApiController
{

    /**
     * @return  \Illuminate\Http\JsonResponse
     */
    public $sabre;
    public $sabre_cars;
    public function __construct(){
      $this->sabre = new SabreActions();
      $this->sabre_cars_rest = new SabreCars();
      $this->sabre_cars_soap = new SabreCarsSoap();
    }
    public function GetBFM(BargainFinderMaxRequest $request)
    {
        return false;
		//return response()->json($this->sabre->BargainFinderMax());
    }
    public function CreatePnr(BookingRequest $request){
      //return response()->json($this->sabre->CreatePnr());
      return response()->json($this->call(SabreBook::class));
      //return ;
    }
    public function GetItinerary(){
      return response()->json($this->sabre->GetItinerary());
    }
    public function CacheBFM(BookingCacheRequest $request){
      return response()->json($this->sabre->BFMCache());
    }
    public function getPolicies(){
      return SabrePolicies::getSabrePolicies();
    }
    public function getAlternateDays(){
      return response()->json($this->call(AlternateDays::class));
    }
    public function getCarAvailability(){
      return $this->sabre_cars_rest->getAvailabilty();
    }
    public function getCarAvailabilitySoap(){
      return $this->sabre_cars_soap->getAvailabilty();
    }
    public function getCarLocationByAirport(){
      return $this->sabre_cars_soap->getCarLocationByAirport();
    }
    public function getCarLocation(){
      return $this->sabre_cars_soap->getCarLocation();
    }
    public function getCarDetailsLocation(){
      return $this->sabre_cars_soap->getCarDetailsLocation();
    }
    public function getBookCar(){
      return $this->sabre_cars_soap->getBooking();
    }
}
