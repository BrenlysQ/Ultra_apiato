<?php

namespace App\Containers\Kiu\Actions\Requests;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use App\Commons\CommonActions;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuParseResponse;
use Illuminate\Support\Facades\Storage;

class PriceRequest extends Action {
	
  public static function GetOneWayPrice($segment,$class){
    
    $TimeStamp = date("c");
    $PseudoCityCode = 'MYC';
    $ISOCountry = 'VE';
    $ISOCurrency = 'VEF'; //VEF

    /*$segment = (object) array(
      "DepartureAirport" => $segment->DepartureAirport,
      "ArrivalAirport" => $segment->ArrivalAirport,
      "DepartureDateTime" => $segment->DepartureDateTime,
      "ArrivalDateTime" => $segment->ArrivalDateTime,
      "ResBookDesigCode" => $class,
      "FlightNumber" => $segment->FlightNumber,
      "MarketingAirline" => $segment->MarketingAirline->CompanyShortName
    );*/

    $vuelta = (object) array(
      "DepartureAirport" => 'MIA',
      "ArrivalAirport" => 'VLN',
      "DepartureDateTime" => '2017-06-06 17:30:00',
      "ArrivalDateTime" => '2017-06-06 20:30:00',
      "ResBookDesigCode" => 'Y',
      "FlightNumber" => '821',
      "MarketingAirline" => 'AG'
    );

    $PassengerTypeCode = 'ADT';
    $PassengerTypeQuantity = '1';

    $roundtrip = false;

    $segmentxml = '<OriginDestinationOption>
              <FlightSegment DepartureDateTime="'.$segment->DepartureDateTime.'" ArrivalDateTime="'.$segment->ArrivalDateTime.'" FlightNumber="'.$segment->FlightNumber.'" ResBookDesigCode="'.$class.'" >
                <DepartureAirport LocationCode="'.$segment->DepartureAirport.'"/>
                <ArrivalAirport LocationCode="'.$segment->ArrivalAirport.'"/>
                <MarketingAirline Code="'.$segment->MarketingAirline.'"/>
              </FlightSegment>
            </OriginDestinationOption>';

    $vueltaxml ='<OriginDestinationOption>
                <FlightSegment DepartureDateTime="'.$vuelta->DepartureDateTime.'" ArrivalDateTime="'.$vuelta->ArrivalDateTime.'" FlightNumber="'.$vuelta->FlightNumber.'" ResBookDesigCode="'.$vuelta->ResBookDesigCode.'" >
                  <DepartureAirport LocationCode="'.$vuelta->DepartureAirport.'"/>
                  <ArrivalAirport LocationCode="'.$vuelta->ArrivalAirport.'"/>
                  <MarketingAirline Code="'.$vuelta->MarketingAirline.'"/>
                </FlightSegment>
              </OriginDestinationOption>';
    
    $request = '<?xml version="1.0" encoding="UTF-8"?>
                <KIU_AirPriceRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="'.getenv('KIU_TARGET').'" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us">
                   <POS>
                      <Source AgentSine="'.getenv('KIU_USERID').'" PseudoCityCode="'.$PseudoCityCode.'" ISOCountry="'.$ISOCountry.'" ISOCurrency="'.$ISOCurrency.'" TerminalID="'.getenv('KIU_TERMINALID').'"></Source>
                   </POS>
                  <AirItinerary>
                    <OriginDestinationOptions>';
                      if($roundtrip) {
                        $request .= $segmentxml.$vueltaxml;
                      } else {
                        $request .= $segmentxml;
                      }
                  $request.= 
                   '</OriginDestinationOptions>
                  </AirItinerary>
                  <TravelerInfoSummary>
                    <AirTravelerAvail>
                      <PassengerTypeQuantity Code="'.$PassengerTypeCode.'" Quantity="'.$PassengerTypeQuantity.'"/>
                    </AirTravelerAvail>
                  </TravelerInfoSummary>
                </KIU_AirPriceRQ>';

    $response = KiuAuth::MakeRequest($request);
    //Storage::append('file.log', 'Appended Text');
    Storage::disk('cachekiu')->append('GetPrice.txt', $response);
    //echo ($response);
    return json_decode(CommonActions::XML2JSON($response));
    //return CommonActions::XML2JSON($response);
  }
  
}
?>