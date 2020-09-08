<?php
namespace App\Containers\Sabre\Data;
use App\Containers\UltraApi\Actions\Prices\PriceHandler;
use App\Commons\CommonActions;
use App\Containers\Sabre\Data\OdoLegStructure;
class OdoStructure{
  public $outbound;
  public $return;
  public $price;
  public $seqnumber;
  public function __construct($data, $payload) {
    //dd($data,$payload);
    $flight = $data->AirItinerary->OriginDestinationOptions;
    $price = $data->AirItineraryPricingInfo;
    $this->seqnumber = $data->SequenceNumber;
    $this->outbound[0] = new OdoLegStructure($flight->OriginDestinationOption[0],'',$data->SequenceNumber);
    if(CommonActions::isRoundTrip($payload->trip)){ //Si es n roundtrip simplemente agrego el retorno
      $this->return[0] = new OdoLegStructure($flight->OriginDestinationOption[1],'',$data->SequenceNumber);
      //$this->returnflight->ifootprint .= $price->ItinTotalFare->TotalFare->Amount;
    }
    $this->price = PriceHandler::PriceBuild($price);
  }

}
?>
