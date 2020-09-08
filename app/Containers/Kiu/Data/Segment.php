<?php

namespace App\Containers\Kiu\Data;
use App\Commons\Airports;
use App\Commons\Airline;
class Segment
{
  public $origin;
  public $destination;
  public $availclasses = array();
  public $airline;
  public $dtime;
  public $atime;
  public $flightno;
  public $elapsedtime;
  public function __construct($opt, $policies){
    //Consulto informacion de los aeropuerto origin/destination y la info de la aerolinea que maneja este segmento
    $airport = new Airports($opt->DepartureAirport->LocationCode);
    $this->origin = $airport->data;
    $airport = new Airports($opt->ArrivalAirport->LocationCode);
    $this->destination = $airport->data;
    $airline = new Airline($opt->MarketingAirline->CompanyShortName);
    $this->airline = $airline->data;
    //Si la aerolinea no tiene politicas para este segemento
    if(!$policies){
      $policies = array();
    }
    $this->dtime = $opt->DepartureDateTime;
    $this->atime = $opt->ArrivalDateTime;
    $this->flightno = $opt->FlightNumber;
    //Cuando hay una sola clase disponible el webservice envia BookingClassAvail
    //no como un array si no como una variable unica, cuando eso sucede
    //en la siguiente rutina se normaliza a un array si es el caso
    if(!is_array($opt->BookingClassAvail)){
      $opt->BookingClassAvail = array($opt->BookingClassAvail);
    }

    //Armar clases disponibles
    foreach($opt->BookingClassAvail as $class){
      //Descartamos la clases que esten en Lista de espera ResBookDesigQuantity = L
      if(is_numeric($class->ResBookDesigQuantity) && $class->ResBookDesigQuantity > 0){
        //Si existen politicas para este segmento y la clase esta includia en esas politicas
        //La agrega al array availclasses
        if(count($policies) > 0 && in_array($class->ResBookDesigCode, $policies)){
          $this->availclasses[] = $class->ResBookDesigCode;
        }elseif(count($policies) == 0){
          $this->availclasses[] = $class->ResBookDesigCode;
        }
        //$this->payloads[] = $this->Payload($class->ResBookDesigCode);
      }
    }
    if(count($this->availclasses) == 0){
      return false;
    }
  }

}
