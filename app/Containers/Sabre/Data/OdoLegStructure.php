<?php

namespace App\Containers\Sabre\Data;
use App\Commons\CommonActions;
use App\Containers\Sabre\Actions\Airports;
use App\Containers\Sabre\Actions\FlightSegment;
class OdoLegStructure{
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
    $destination = '';
    $lastarrival = '';
    if(is_array($segments->FlightSegment)){
      $this->havestops = true;
      foreach ($segments->FlightSegment as $segnumb => $segment) {
        if(!in_array($segment->MarketingAirline->Code,$this->airlines)){
          $this->airlines[] = $segment->MarketingAirline->Code;
        }
        if($segnumb == 0){
          $airport = new Airports($segment->DepartureAirport->LocationCode);
          $this->origin = $airport->BasicInfo();
          $this->dtime = str_replace("T", " ", $segment->DepartureDateTime);
        }

        if(count($segments->FlightSegment) == ($segnumb + 1)){
          $airport = new Airports($segment->ArrivalAirport->LocationCode);
          $this->destination = $airport->BasicInfo();
          $this->atime = CommonActions::RemoveT($segment->ArrivalDateTime);
        }
        if($segnumb > 0){
          $ai = $segment->DepartureAirport->LocationCode;
          $dt = CommonActions::RemoveT($segment->DepartureDateTime);
          $this->stops[] = OdoLegStructure::HandleStops($ai,$lastarrival,$dt);
        }
        $this->segments[] = new FlightSegment($segment,$baggage);
      }
    }else{
      $segment = $segments->FlightSegment;
      $this->airlines[] = $segment->MarketingAirline->Code;
      $airport = new Airports($segment->DepartureAirport->LocationCode);
      $this->origin = $airport->BasicInfo();
      $this->dtime = str_replace("T", " ", $segment->DepartureDateTime);
      $airport = new Airports($segment->ArrivalAirport->LocationCode);
      $this->destination = $airport->BasicInfo();
      $this->atime = CommonActions::RemoveT($segment->ArrivalDateTime);
      $this->segments[] = new FlightSegment($segment,$baggage);
    }
    // foreach(){
    //
    //   $this->footprint .= $segment->DepartureAirport->LocationCode . '-' . $segment->ArrivalAirport->LocationCode . '-' . $segment->FlightNumber;
    //   $this->ifootprint .= $segment->DepartureAirport->LocationCode . '-' . $segment->ArrivalAirport->LocationCode . '-' . $segment->MarketingAirline->Code;
    //   //$destination = $segment->ArrivalAirport->LocationCode;
    //   //$this->atime = CommonActions::RemoveT($segment->ArrivalDateTime);
    //   $lastarrival = CommonActions::RemoveT($segment->ArrivalDateTime);
    // }
    //$this->footprint .= '-' . $destination;
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
