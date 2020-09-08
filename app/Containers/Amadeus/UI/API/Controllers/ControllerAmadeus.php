<?php

namespace App\Containers\Amadeus\UI\API\Controllers;
use App\Commons\TagsGdsHandler;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Support\Facades\Config;
use App\Containers\Amadeus\Actions\AmadeusHandler;

class ControllerAmadeus extends ApiController
{
    public $amadeus;
    public function __construct(AmadeusHandler $amadeus){
        $this->amadeus = $amadeus;
    }
    public function GetFlights(){
        //die('sadasd');
      return $this->amadeus->GetFlights();
    }
    public function CreatePnr(){
      return $this->amadeus->CreatePNR();
    }
    public function PnrRetrieve(){
        return $this->amadeus->RetrievePNR();
    }
    public function getCalendar(){
        return $this->amadeus->GetCalendar();
    }
    public function issueTicket(){
        return $this->amadeus->IssueTicket();
    }
    /*public function CreatePNR(){
      return $this->amadeus->CreatePNR();
    }
    public function PricePNR(){
      return $this->amadeus->PricePNR();
    }
    public function StoreFare(){
        return $this->amadeus->StoreFare();
    }
    public function CreateFOP(){
        return $this->amadeus->CreateFOP();
    }
    public function SavePNR(){
        return $this->amadeus->SavePNR();
    }*/
    public function AmadeusCache(){
        return $this->amadeus->AmadeusCache();
    }
}
