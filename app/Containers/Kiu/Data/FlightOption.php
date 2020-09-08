<?php

namespace App\Containers\Kiu\Data;
use App\Containers\Kiu\Data\Segment;
use Carbon\Carbon;
use App\Containers\Kiu\Actions\AlPoliciesHandler;
use App\Commons\Airports;
use App\Commons\CommonActions;
use App\Commons\Airline;
class FlightOption
{
  public $stops = array();
  public $havestops = false;
  public $segments = array();
  public $origin;
  public $destination;
  public $plcardinality = 1;
  public $dtime;
  public $atime;
  public $airlines = array();
  public $payloads = array();
  public $footprints = array();
  public $human_read = array();
  public $segment_classes = array();
  public $elapsedtime = 0;
  public $governor_carrier = '';
  public $seqnumber;
  public $classes;
  public function __construct($opt, $payload, $sniffer = true){
    //Construyo los segmentos para cada opcion
    //Si hay mas de un segmento
    if(is_array($opt->FlightSegment)){
      $lastarrival = '';
      foreach ($opt->FlightSegment as $key => $seg) {
        $policies = AlPoliciesHandler::Get($seg, $payload);
        //print_r($policies); die;
        if(!$this->CreateSegment($seg, $policies)){
          return false;
        }

        //[]
        //Si es sniffer es false quiere decir que la solicitud vienen de un searchflight
        // si es true quiere decir que es el sniffer de las tarifas
        //Cuando la solicitud viene del search fligt debe rellenar estos campos que son vitales
        //para construir el ODO en Vue (UltraSite)
        if(!$sniffer){
          if(!in_array($seg->MarketingAirline->CompanyShortName,$this->airlines)){
            $this->airlines[] = $seg->MarketingAirline->CompanyShortName;
          }
          //$this->segments[$key]->airline = 'Hello api';
          if($key == 0){
            $airport = new Airports($seg->DepartureAirport->LocationCode);
            $this->origin = $airport->BasicInfo();
            $this->dtime = $seg->DepartureDateTime;
          }else{
            $iata = $seg->DepartureAirport->LocationCode;
            $this->stops[] = $this->HandleStops($iata,$lastarrival,$seg->DepartureDateTime);
          }
          if(count($opt->FlightSegment) == ($key + 1)){
            $this->atime = $seg->ArrivalDateTime;
            $airport = new Airports($seg->ArrivalAirport->LocationCode);
            $this->destination = $airport->BasicInfo();
          }
          $elapsed = CommonActions::HourToMins($seg->JourneyDuration);
          $this->segments[$key]->elapsedtime = $elapsed;
          $lastarrival = $seg->ArrivalDateTime;
          $this->havestops = true;
          $this->elapsedtime += CommonActions::HourToMins($seg->JourneyDuration);
        }
      }
      //print_r($this->segments); die;
    //Si es un vuelo directo
    }else{
      $seg = $opt->FlightSegment;
      $policies = AlPoliciesHandler::Get($seg, $payload);
      if(!$this->CreateSegment($seg, $policies)){
        return false;
      }
      //Si es sniffer es false quiere decir que la solicitud vienen de un searchflight
      // si es true quiere decir que es el sniffer de las tarifas
      //Cuando la solicitud viene del search fligt debe rellenar estos campos que son vitales
      //para construir el ODO en Vue (UltraSite)
      if(!$sniffer){
        $elapsed = CommonActions::HourToMins($seg->JourneyDuration);
        $this->segments[0]->elapsedtime = $elapsed;
        $this->airlines[] = $seg->MarketingAirline->CompanyShortName;
        $airport = new Airports($seg->DepartureAirport->LocationCode);
        $this->origin = $airport->BasicInfo();
        $airport = new Airports($seg->ArrivalAirport->LocationCode);
        $this->destination = $airport->BasicInfo();
        $this->dtime = $seg->DepartureDateTime;
        $this->atime = $seg->ArrivalDateTime;
        $this->elapsedtime = $elapsed;
      }
    }
    //print_r($this->segments); die;
    $this->PayLoads();
  }

  private function HandleStops($iatacode,$arrival,$departure){
    $airport = new Airports($iatacode);
    return array(
      "airport" => $airport->BasicInfo(),
      //"WaitTime" => $departure
      "waittime" => CommonActions::DateDiff($arrival,$departure)
    );
  }

  //Obtengo los posibles PAyloads para cada segmento de
  private function PayLoads(){
    //SI tiene escalas es decir mas de un segmento
    if(count($this->segments) > 1){
      //Inicializar array Payloads
      for ($i = 0; $i < $this->plcardinality; $i++) {
        $this->payloads[$i] = '';
        $this->footprints[$i] = '';
        $this->human_read[$i] = '';
        $this->segment_classes[$i] = array();
      }

      foreach ($this->segments as $key => $segment) {
        $sp = count($segment->availclasses);
        if($key == 0){
          $pivot = $this->plcardinality / $sp;
        }else{
          $pivot = $pivot / $sp;
        }
        $prev = 0;
        $j = 0;

        while($j < $this->plcardinality){
          if(!is_array($segment->availclasses)){
            print_r($segment->availclasses); die;
          }
          foreach($segment->availclasses as $class){
            for($i = 0; $i < $pivot; $i++){
              $this->payloads[$j] .= $this->SetPayload($segment, $class);
              $dt1 = Carbon::parse($segment->dtime);
              $this->footprints[$j] .= $this->GetFootPrint($segment, $class);
              $this->human_read[$j] .= $segment->origin->IATA. '/' . $segment->destination->IATA . ' ' . $dt1->toDateString()
              . ' ' . $segment->airline->iata . ' ' . $segment->flightno . ' ' . $class . ' - ';
              $this->segment_classes[$j][]= $class;
              $j++;
            }
          }
        }


      }
    //SI es vuelo directo es decir uno y solo un segmento
    }else{
      //print_r($this->segments[0]); die;
      $segment = $this->segments[0];
      foreach($segment->availclasses as $class){
        //for($i = 0; $i < $this->plcardinality; $i++){
        $dt1 = Carbon::parse($segment->dtime);
        $this->segment_classes[][] = $class;
        $this->payloads[] = $this->SetPayload($segment, $class);
        $this->footprints[] = $this->GetFootPrint($segment, $class);
        $this->human_read[] = $segment->origin->IATA. '/' . $segment->destination->IATA . ' ' . $dt1->toDateString()
        . ' ' . $segment->airline->iata . ' ' . $segment->flightno . ' ' . $class . ' - ';
        //}
      }
    }
  }

  private function CreateSegment($seg, $policies){
    if(count($this->segments) == 0){
      $this->governor_carrier = $seg->MarketingAirline->CompanyShortName;
    }
    if($this->governor_carrier != $seg->MarketingAirline->CompanyShortName){
      $this->segments = array();
      return false;
    }
    $segment = new Segment($seg, $policies);
    if($segment->availclasses){
      $this->segments[] = $segment;
      $this->plcardinality = $this->plcardinality * count($segment->availclasses);
      return true;
    }else{
      $this->segments = array();
      return false;
    }
  }
  private function SetPayload($segment, $class){
    $payload = '<FlightSegment DepartureDateTime="'.$segment->dtime.'"
              ArrivalDateTime="'.$segment->atime.'"
              FlightNumber="'.$segment->flightno.'"
              ResBookDesigCode="'.$class.'" >
                <DepartureAirport LocationCode="'.$segment->origin->IATA.'"/>
                <ArrivalAirport LocationCode="'.$segment->destination->IATA.'"/>
                <MarketingAirline Code="' . $segment->airline->iata . '"/>
              </FlightSegment>';
    return $payload;
  }
  private function GetFootPrint($segment, $class){
    $dt1 = Carbon::parse($segment->dtime);
    $dt2 = Carbon::parse($segment->atime);
    return $segment->origin->IATA . '-' . $segment->destination->IATA . '-' . $dt1->toDateString() . '-' .
    $dt2->toDateString() . '-' . $segment->airline->iata . '-' . $segment->flightno . '-' . $class;
  }
}
