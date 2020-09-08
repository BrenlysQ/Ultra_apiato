<?php

namespace App\Containers\Kiu\Actions\Requests;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Log;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuParseResponse;
use App\Commons\CommonActions;

class AvailabilityRequest extends Action {

  public static function KIU_AirAvailRQ($payload) {

    $TimeStamp = date("c");

    $request='<?xml version="1.0" encoding="UTF-8"?>
                <KIU_AirAvailRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="'.getenv('KIU_TARGET').'" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us" DirectFlightsOnly="false">
                   <POS>
                      <Source AgentSine="'.getenv('KIU_USERID').'" TerminalID="'.getenv('KIU_TERMINALID').'" />
                   </POS>
                   <OriginDestinationInformation>
                      <DepartureDateTime>'.$payload->departure_date.'</DepartureDateTime>
                      <OriginLocation LocationCode="'.$payload->departure_city.'" />
                      <DestinationLocation LocationCode="'.$payload->destination_city.'" />
                   </OriginDestinationInformation>
                   <OriginDestinationInformation>
                      <DepartureDateTime>'.$payload->return_date.'</DepartureDateTime>
                      <OriginLocation LocationCode="'.$payload->destination_city.'" />
                      <DestinationLocation LocationCode="'.$payload->departure_city.'" />
                   </OriginDestinationInformation>
                   <TravelPreferences>
                      <CabinPref Cabin="" />
                   </TravelPreferences>
                   <TravelerInfoSummary>
                      <AirTravelerAvail>
                        <PassengerTypeQuantity Code="ADT" Quantity="'.$payload->adult_count.'" />
                      </AirTravelerAvail>
                   </TravelerInfoSummary>
                </KIU_AirAvailRQ>';
               /* <TravelPreferences>
                    <CabinPref Cabin="'.$payload->cabin.'" />
                 </TravelPreferences>
                 <PassengerTypeQuantity Code="CNN" Quantity="'.$payload->child_count.'" />
  */

    //$response = KiuAuth::MakeRequest($request);
    //echo $response; //die;
    //echo $request; //die;
    //$json = json_decode(CommonActions::XML2JSON($response));
  //  $AirAvailRS = KiuParseResponse::AirAvailRS(json_decode($json),$payload);
    return $request;
  }
}
?>
