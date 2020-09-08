<?php
namespace App\Containers\Kiu\Data;
use App\Ship\Parents\Actions\Action;
use App\Containers\Kiu\Actions\PriceHandler;
class TravelOption extends Action{
  public $outbound;
  public $return;
  public $price;
  public $footprint;
  public $seqnumber;
  public function __construct($data, $found) {
    $outfl = clone $data->outopt;
    $retfl = clone $data->retopt;
    //$this->seqnumber = $data->SequenceNumber;
    // $this->outboundflight = new FligthOption($flight->OriginDestinationOption[0],'',$data->SequenceNumber);
    // $this->returnflight = new FligthOption($flight->OriginDestinationOption[1],'',$data->SequenceNumber);
    $this->CleanOpt($outfl);
    $this->CleanOpt($retfl);
    $outfl->classes = $data->outclasses;
    $retfl->classes = $data->retclasses;
    $this->outbound[0] = $outfl;
    $this->return[0] = $retfl;
    $this->footprint = $data->footprint;

    $this->seqnumber = $found;
    $this->outbound[0]->seqnumber = $found;
    $this->return[0]->seqnumber = $found;
    $this->price = PriceHandler::PriceBuild($data);
    //print_r($this->price->airpricinginfo);
    //$this->price->airpricinginfo = json_encode($this->price->airpricinginfo);
    //$this->footprint = $this->HandleFootPrint();
  }
  //Metodo para remover de los options algunos datos inncesarios para el searchflight
  //Pero necesarios
  private function CleanOpt($opt){
    unset($opt->payloads);
    unset($opt->footprints);
    unset($opt->human_read);
    unset($opt->plcardinality);
    unset($opt->segment_classes);
  }
}
?>
