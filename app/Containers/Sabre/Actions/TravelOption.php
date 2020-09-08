<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\UltraApi\Actions\Prices\PriceHandler;
use App\Commons\CommonActions;
class TravelOption extends Action{
  public $outboundflight;
  public $returnflight;
  public $price;
  public $footprint;
  public $seqnumber;
  public function __construct($data, $payload) {
    //print_r($data); die;
    $flight = $data->AirItinerary->OriginDestinationOptions;
    (is_array($data->AirItineraryPricingInfo)) ? $price = $data->AirItineraryPricingInfo[0] : $price = $data->AirItineraryPricingInfo;
    //$baggage = $data->AirItineraryPricingInfo[0]->PTC_FareBreakdowns->PTC_FareBreakdown[0]->PassengerFare->TPA_Extensions->BaggageInformationList->BaggageInformation;
    $this->seqnumber = $data->SequenceNumber;
    //dd($flight->OriginDestinationOption);
    $this->outboundflight = new FligthOption($flight->OriginDestinationOption[0],'',$data->SequenceNumber);
    $this->outboundflight->ifootprint .= $price->ItinTotalFare->TotalFare->Amount; //FOOT PRINT NECESARIO PARA SORTEDRESULT MD5(FECHA: BLA)
    if(CommonActions::isRoundTrip($payload->trip)){ //Si es n roundtrip simplemente agrego el retorno
      $this->returnflight = new FligthOption($flight->OriginDestinationOption[1],'',$data->SequenceNumber);
      $this->returnflight->ifootprint .= $price->ItinTotalFare->TotalFare->Amount;
    }
    $this->price = PriceHandler::PriceBuild($price);
    //$this->footprint = $this->HandleFootPrint();
  }

}
?>
