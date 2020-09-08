<?php

namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\CommonActions;
class FligthOption extends Action{
  public $stops;
  public $havestops;
  public $origin;
  public $destination;
  public $dtime;
  public $atime;
  public $airlines;
  public $segments;
  public $elapsedtime;
  public $ifootprint;
  public $footprint;
  public $seqnumber;
  public function __construct($segments,$baggage,$seqnumber) {
    $this->stops = array();
    $this->havestops = false;
    $this->airlines = array();
	  $this->segments = array();
    $this->seqnumber = $seqnumber;
    $this->elapsedtime = $segments->ElapsedTime;

    if(!is_array($segments->FlightSegment)){
      FligthOption::construye($segments->FlightSegment,0,$baggage,1);
    }else{
      foreach($segments->FlightSegment as $segnumb => $segment){
        FligthOption::construye($segment,$segnumb,$baggage,count($segments->FlightSegment));
      }
    }

    //$this->footprint .= '-' . $destination;
  }
  private function construye($segment,$segnumb,$baggage,$segqty){
    $destination = '';
    $lastarrival = '';
    if(!in_array($segment->MarketingAirline->Code,$this->airlines)){
      $this->airlines[] = $segment->MarketingAirline->Code;
    }
    if($segnumb == 0){
      $airport = new Airports($segment->DepartureAirport->LocationCode);
      $this->origin = $airport->BasicInfo();
      $this->dtime = str_replace("T", " ", $segment->DepartureDateTime);
    }
    if($segment->StopQuantity > 0){

      //Creo segmentos ocultos en las paradas de lo vuelos por ejemplo AUA
      $seg = '{
        "DepartureAirport" : {
          "LocationCode" : "' . $segment->DepartureAirport->LocationCode . '"
        },
        "DepartureDateTime" : "' . $segment->DepartureDateTime . '",
        "ArrivalDateTime" : "' . $segment->StopAirports->StopAirport[0]->ArrivalDateTime . '",
        "ArrivalAirport" : {
          "LocationCode" : "' . $segment->StopAirports->StopAirport[0]->LocationCode . '"
        },
        "MarketingAirline" : {
          "Code" : "' . $segment->MarketingAirline->Code . '"
        },
        "FlightNumber" : "' . $segment->FlightNumber . '",
        "ElapsedTime" : "' . $segment->ElapsedTime . '"
      }';
      $this->segments[] = new FlightSegment(json_decode($seg),$baggage);
      $seg = '{
        "DepartureAirport" : {
          "LocationCode" : "' . $segment->StopAirports->StopAirport[0]->LocationCode . '"
        },
        "DepartureDateTime" : "' . $segment->StopAirports->StopAirport[0]->DepartureDateTime . '",
        "ArrivalDateTime" : "' . $segment->ArrivalDateTime . '",
        "ArrivalAirport" : {
          "LocationCode" : "' . $segment->ArrivalAirport->LocationCode . '"
        },
        "MarketingAirline" : {
          "Code" : "' . $segment->MarketingAirline->Code . '"
        },
        "FlightNumber" : "' . $segment->FlightNumber . '",
        "ElapsedTime" : "' . $segment->StopAirports->StopAirport[0]->ElapsedTime . '"
      }';
      $this->segments[] = new FlightSegment(json_decode($seg),$baggage);
    }
    else{
      $this->segments[] = new FlightSegment($segment,$baggage);
    }
    if($segqty == ($segnumb + 1)){
      $airport = new Airports($segment->ArrivalAirport->LocationCode);
      $this->destination = $airport->BasicInfo();
      $this->atime = CommonActions::RemoveT($segment->ArrivalDateTime);
    }
    if($segnumb > 0){
      $ai = $segment->DepartureAirport->LocationCode;
      $dt = CommonActions::RemoveT($segment->DepartureDateTime);
      $this->stops[] = FligthOption::HandleStops($ai,$lastarrival,$dt);
      $this->havestops = true;
    }
    $this->footprint .= $segment->DepartureAirport->LocationCode . '-' . $segment->ArrivalAirport->LocationCode . '-' . $segment->FlightNumber;
    $this->ifootprint .= $segment->DepartureAirport->LocationCode . '-' . $segment->ArrivalAirport->LocationCode . '-' . $segment->MarketingAirline->Code;
    //$destination = $segment->ArrivalAirport->LocationCode;
    //$this->atime = CommonActions::RemoveT($segment->ArrivalDateTime);
    $lastarrival = CommonActions::RemoveT($segment->ArrivalDateTime);
  }
  private static function HandleStops($iatacode,$arrival,$departure){
    $airport = new Airports($iatacode);
    return array(
      "airport" => $airport->BasicInfo(),
      //"WaitTime" => $departure
      "waittime" => CommonActions::DateDiff($arrival,$departure)
    );
  }
  public function GetFootprint(){
    return $this->footprint;
  }
  public function GetIfootPrint(){
    return $this->ifootprint;
  }
}
?>
