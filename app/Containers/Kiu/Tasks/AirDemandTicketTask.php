<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Tasks\CredentialsTaks;
use Carbon\Carbon;
use App\Containers\Kiu\Models\AirlineInvoiceCodeModel;

class AirDemandTicketTask extends Task
{
  public function run($data,$currency)
  {
    // dd($data->TravelItinerary->ItineraryInfo->ReservationItems);
    // if (is_array($data->TravelItinerary->ItineraryInfo->ReservationItems)) {
    //   $companycode =  $data->TravelItinerary->ItineraryInfo->ReservationItems->Item[0]->Air->Reservation->MarketingAirline;
    // }else {
    //   $companycode = $data->TravelItinerary->ItineraryInfo->ReservationItems->Item->Air->Reservation->MarketingAirline;
    // }
    //$airline = AirlineInvoiceCodeModel::where('airline_code', )->first();
    $TimeStamp = date("c");
    $credentials = (new CredentialsTaks())->run($currency);
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
    <KIU_AirDemandTicketRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="Production" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us">
      <POS>
        <Source AgentSine="'.$credentials->UserID.'" TerminalID="'.$credentials->TerminalID.'" ISOCountry="'.$credentials->ISOCountry.'" ISOCurrency="'.$credentials->ISOCurrency.'">
          <RequestorID Type="5"/>
          <BookingChannel Type="1"/>
        </Source>
      </POS>
      <DemandTicketDetail TourCode="">
        <BookingReferenceID ID="'.$data->TravelItinerary->ItineraryRef->ID.'">
          <CompanyName Code="'.$data->TravelItinerary->ItineraryInfo->ReservationItems->Item[0]->Air->Reservation->MarketingAirline.'"/>
        </BookingReferenceID>
        <PaymentInfo PaymentType="1">
          <ValueAddedTax VAT=""/>
        </PaymentInfo>
        </DemandTicketDetail>
      </KIU_AirDemandTicketRQ>';
	  return $xml;
  }
}
