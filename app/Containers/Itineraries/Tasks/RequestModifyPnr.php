<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Tasks\CredentialsTaks;
use Carbon\Carbon;


class RequestModifyPnr extends Task
{
    public function run($data)
    {

      $TimeStamp = date("c");
      $credentials = (new CredentialsTaks())->run($data->itinerary->currency);
      $xml_fuck = '';
      $xml = '<?xml version="1.0" encoding="UTF-8"?>
        <KIU_AirBookModifyRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="Production" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us">
          <POS>
            <Source AgentSine="' . $credentials->UserID . '" TerminalID="' . $credentials->TerminalID . '" ISOCountry="'. $credentials->ISOCountry .'" />
          </POS>
          <AirReservation>
            <BookingReferenceID ID="'.$data->itinerary_id.'" />
            <TravelerInfo>
              <SpecialReqDetails>
                <SpecialServiceRequests>';
      foreach ($data->paxes as $key => $pax) {
        $dob = Carbon::parse($pax->dob)->formatLocalized('%A %d %B %Y');
        $dob = explode(' ', $dob);
        $dob = $dob[1].strtoupper(substr($dob[2],0,3)).substr($dob[3],2,4);
        $due = Carbon::parse($pax->due_date)->formatLocalized('%A %d %B %Y');
        $due = explode(' ', $due);
        $due = $due[1].strtoupper(substr($due[2],0,3)).substr($due[3],2,4);
        $text_pax = $pax->document.'/'.$pax->country_issue.'/'.$pax->passport.'/'.$pax->cob.'/'.$dob.'/'.$pax->gender.'/'.$due.'/'.strtoupper($pax->lastname).'/'.strtoupper($pax->firstname);
        $xml.='
        					<SpecialServiceRequest Number="'.($key+1).'" ServiceQuantity="1" SSRCode="CKIN" Status="HK" TravelerRefNumberRPHList="0'.($key+1).'">
                    <FlightLeg Date="'.$data->date_departure.'">
                      <DepartureAirport LocationCode="'.$data->itinerary->departure_city.'" />
                      <ArrivalAirport LocationCode="'.$data->itinerary->destination_city.'" />
                    </FlightLeg>
                    <Text>'.$text_pax.'</Text>
        					</SpecialServiceRequest>';
        //$text = array($key => ''.$pax->document.'/'.$pax->country_issue.'/'.$pax->passport.'/'.$pax->cob.'/'.$dob.'/'.$pax->gender.'/'.$due.'/'.strtoupper($pax->lastname).'/'.strtoupper($pax->firstname).'' );
        $xml_fuck .= '<SpecialServiceRequest Number="'.($key+1).'" ServiceQuantity="1" SSRCode="CKIN" Status="HK" TravelerRefNumberRPHList="0'.($key+1).'">
                        <Airline Code="XX" />
                        <Text>' . $text_pax . '</Text>
                    </SpecialServiceRequest>';
      }
      $xml .='
                </SpecialServiceRequests>
              </SpecialReqDetails>
            </TravelerInfo>
          </AirReservation>
          <AirBookModifyRQ>
            <TravelerInfo>
                <SpecialReqDetails>
                    <SpecialServiceRequests>
                        ' . $xml_fuck . '
                    </SpecialServiceRequests>
                </SpecialReqDetails>
            </TravelerInfo>
          </AirBookModifyRQ>
        </KIU_AirBookModifyRQ>';
      //echo($xml); die;
  	  return $xml;
    }

}
