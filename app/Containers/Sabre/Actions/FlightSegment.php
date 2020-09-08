<?php

namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\CommonActions;
class FlightSegment extends Action{
  public $origin;
  public $destination;
  public $dtime;
  public $atime;
  public $airline;
  public $flightno;
  public $baggage;
  public $elapsedtime;
  public $classbook;
  public function __construct($segment,$baggage) {
    $airport = new Airports($segment->DepartureAirport->LocationCode);
  	$this->origin = $airport->data;
  	$this->dtime = CommonActions::RemoveT($segment->DepartureDateTime);
  	$this->flightno = $segment->FlightNumber;
    $airline = new Airline($segment->MarketingAirline->Code);
  	$this->airline = $airline->BasicInfo();
    $airport = new Airports($segment->ArrivalAirport->LocationCode);
  	$this->destination = $airport->data;

    (!property_exists($segment,'ResBookDesigCode')) ? $this->classbook = '' : $this->classbook = $segment->ResBookDesigCode;;
  	$this->atime = CommonActions::RemoveT($segment->ArrivalDateTime);
    (property_exists($segment,'ElapsedTime')) ? $this->elapsedtime = $segment->ElapsedTime : $this->elapsedtime = '';
  }
}
?>
