<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Kiu\Data\FlightOption;
use App\Containers\Kiu\Tasks\PricePayloadTask;
use App\Containers\Kiu\Models\FaresCacheModel;
use App\Containers\Kiu\Actions\FareHandler;
use App\Containers\Kiu\Actions\TOptionsHandler;
use App\Commons\Logger;
use App\Commons\CommonActions ;
class AirPriceTask extends Task
{

    public $retoptions = array();
    public $outrequests = array();
    public $retrequests = array();
    public $plcardinality = 1;
    public $ptype = 'ADT';
    public $sniffer;
    public $currency = 'VEF';
    protected $payload;
    protected $pid;
    protected $logfile;
    protected $spacer = '
';
    private function ValidateAirAvail($airavail){

      if(!is_object($airavail) || !property_exists($airavail,'OriginDestinationInformation')){
        return false;
      }else{
        if(!is_array($airavail->OriginDestinationInformation)){
          return false;
        }else{
          $out = $airavail->OriginDestinationInformation[0];
          $ret = $airavail->OriginDestinationInformation[1];
          if(!property_exists($out,'OriginDestinationOptions') || !property_exists($ret,'OriginDestinationOptions')){
            return false;
          }else{
            if(empty($out->OriginDestinationOptions) || empty($ret->OriginDestinationOptions)){
              if(empty($out->OriginDestinationOptions)){
                return 1000;
              }
              return false;
            }
            //print_r($ret->OriginDestinationOptions); die;
            $out = $out->OriginDestinationOptions;
            $ret = $ret->OriginDestinationOptions;
            if(!property_exists($out,'OriginDestinationOption') || !property_exists($ret,'OriginDestinationOption')){
              return false;
            }else{
              return true;
            }
          }
        }
      }
    }
    public function run($airavail, $payload, $sniffer = true)
    {
      $this->payload = $payload;
      $this->sniffer = $sniffer;
      $this->currency = $payload->currency;
      $this->logfile = 'LOG-' . $this->payload->departure_city . '-' . $this->payload->destination_city . '-' . $this->payload->currency;
      $validate = $this->ValidateAirAvail($airavail);
	  $this->pid = getmypid();
      if($validate === true) {
        $out = $airavail->OriginDestinationInformation[0]->OriginDestinationOptions->OriginDestinationOption;
        $ret = $airavail->OriginDestinationInformation[1]->OriginDestinationOptions->OriginDestinationOption;
      }else{
        //Si al respuesta es invalida retorno la respuesta de validate
        return $validate;
      }
      //Si no hay opciones de ida retorno 1000 para saltar al siguiente dia de ida
      if(!$this->outoptions = $this->BuildOptions($out)){
        return 1000;
      }
      if(!$this->retoptions = $this->BuildOptions($ret)){
        return;
      }
      // echo $this->plcardinality;
      //  print_r($this->outoptions);
      //  print_r($this->retoptions);
      //  die;
      //Si es sniffer es false quiere decir que la solicitud vienen de un searchflight
      if(!$this->sniffer){
        //Instancio la clase TOptionsHandler para manejar las opciones que se retornaran en el
        //Searchflight
        $opthandler = new TOptionsHandler();
      }
      $count = 1;
      foreach ($this->outoptions as $key => $outopt) {
        //echo 'Hello'; die;
        //For para recorrer los posibles payloads de la opcion de ida en $OUTOPT
        foreach ($outopt->payloads as $ioutpl => $outpl) {
          //For para recorrer las opciones de vuelta
          foreach ($this->retoptions as $key => $retopt) {
            //For para recorrer los posibles payloads de la opcion de vuelta en $RETPL

            if(($outopt->governor_carrier != $retopt->governor_carrier) || $outopt->governor_carrier  == 'LW' || $retopt->governor_carrier == 'LW'){
              //break;
            }else{
              foreach ($retopt->payloads as $iretpl => $retpl) {
                //Si es sniffer es false quiere decir que la solicitud vienen de un searchflight
                //si es true quiere decir que es el sniffer de las tarifas
                if($this->sniffer){
                  Logger::Write($this->payload->departure_city . '/' . $this->payload->destination_city . ' - Option: (' . $count . '/' . $this->plcardinality . ')',false,$this->logfile);
                  $this->Sniffer($outopt, $ioutpl, $retopt, $iretpl);
                  $count++;
                }else{
                  $footprint = md5($outopt->footprints[$ioutpl] . $retopt->footprints[$iretpl]);
                  //Consulto si hay tarifas disponibles para el footprint analizado

                  if($fare = FareHandler::GetFare($footprint, $this->payload)){
					//dd($fare);
                    $data = CommonActions::CreateObject(); //Creamos un obj StdClass vacio
                    $data->outopt = $outopt;
                    $data->payload = $this->payload;
                    $data->retopt = $retopt;
                    $data->fare = $fare;
                    $data->footprint = $footprint;
                    //EN las siguientes 2 lineas obtenemos las clases para las cuales  hemos encontrado tarifas
                    //validas, las clases van en un array que se corresponde a cada segmento para la ida y para la vuelta
                    $data->outclasses = $outopt->segment_classes[$ioutpl];
                    $data->retclasses = $retopt->segment_classes[$iretpl];
                    //Si consigue tarifas creo un travel option conla ida y la vuelta
          					$opthandler->Add($data);

                  }
                }

              }
            }

          }
        }
      }
      if(!$this->sniffer){
        return $opthandler->GetOpts($payload);
      }
    }
    //metodo provisional para cotizar un itinerario contra el webservice de KIU
    private function Sniffer($outopt, $ioutpl, $retopt, $iretpl){
      $outpl = $outopt->payloads[$ioutpl];
      $retpl = $retopt->payloads[$iretpl];
      $request = (new PricePayloadTask())->run($outpl, $retpl,$this->currency);
      //echo 'strlen($footprint)'; die;
      $response = (new MakeRequestTask())->run($request);
      $human = $outopt->human_read[$ioutpl] . substr($retopt->human_read[$iretpl], 0, -1);
      if(!is_object($response) || property_exists($response,'Error')){
        // Log::debug('
        // Imposible cotizar
        //
        // ' . $footprint . '
        //
        // ' . $outpl . $retpl . '
        //
        // ');
        //$request = CommonActions::XML2JSON($request);
        Logger::Write('Reboto: ' . $human,false,$this->logfile);
        //print_r($request); die;
        //echo 'Imposible cotizar ' . $request->AirItinerary;
      }else{
        $footprint = md5($outopt->footprints[$ioutpl] . $retopt->footprints[$iretpl]);
        $route = $outopt->footprints[$ioutpl] . '-' . $retopt->footprints[$iretpl];
        //$human = $outopt->human_read[$ioutpl] . substr($retopt->human_read[$iretpl], 0, -1);
        $far = $response->PricedItineraries->PricedItinerary->AirItineraryPricingInfo->ItinTotalFare;
        $this->StoreFare($footprint, $this->ptype, $route, $far);
        //print_r($far); die;
        Logger::Write('Tarifa encontrada, ' . $human .
        ' ' . $far->TotalFare->Amount . ' ' . $far->TotalFare->CurrencyCode . ' ' . $this->pid,false,$this->logfile);
      }
    }
    private function StoreFare($footprint, $ptype, $route, $far){
      //$fare = new FaresCacheModel();
      //La siguiente rutina busca si ya existe una tarifa guarada con el footprint indicado
      //Si no la consigue crea una nueva, garantizamos que en $fare siempre habra un mopdelo (FaresCacheModel)
      //de fare valido
      if(!$fare = FaresCacheModel::where('footprint',$footprint)->first()){
        $fare = new FaresCacheModel();
      }
      $fare->expirationdate = $this->payload->departure_date;
      $fare->footprint = $footprint;
      $fare->passengertype = $ptype;
      $fare->class = 'Y';
      $fare->route = $route;
      $fare->currency = $this->currency;
      $fare->airpricinginfo = json_encode($far);
      $fare->totalfare = $far->TotalFare->Amount;
      $fare->save();
    }
    private function BuildOptions($opts){
      $options = array();
      $cardinal = 0;
      if(is_array($opts)){
        foreach ($opts as $key => $opt) {
          $option = new FlightOption($opt, $this->payload, $this->sniffer);
          if(count($option->segments) > 0)
            $options[] = $option;
            $cardinal = $option->plcardinality + $cardinal;
        }
      }else{
        $option = new FlightOption($opts, $this->payload, $this->sniffer);
        if(count($option->segments) > 0){
          $options[] = $option;
          $cardinal = $option->plcardinality + $cardinal;
        }

      }
      $this->plcardinality = $this->plcardinality * $cardinal;
      (count($options) > 0) ? $ret = $options : $ret = false;
      return $ret;
    }

}
