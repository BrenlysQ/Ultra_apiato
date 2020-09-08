<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\TagsGdsHandler;
  class SabreActions extends Action {
    public $BFM;
    public $PNR;
    public function BargainFinderMax(){
        $this->BFM = new BargainFinderMax();
        return $this->BFM->GetFlights();
    }
    public function BFMCache(){
      $this->BFM = new BargainFinderMax();
      return $this->BFM->BFMCache();
    }
    public function GetItinerary(){
      $res = TagsGdsHandler::GetGdsTag();
      return json_decode($res->itinerary);
    }
    public function CreatePnr(){
       $this->PNR = new CreatePassenger();
       //$this->BFM = new BargainFinderMax();

       return $this->PNR->CreateSoap();
    }
    public function alternateDays(){
      $this->BFM = new AlternateDays();
      return $this->BFM->calculateAlternateDays();
    }
  }
 ?>