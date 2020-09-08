<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Tasks\CredentialsTaks;
/**
 * Class BookPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class BookPayloadTask extends Task
{
    public $cache = '';
    public function run($cache,$currency)
    {
      $this->cache = $cache;
      $TimeStamp = date("c");
      $credentials = (new CredentialsTaks())->run($currency);
      $pcc = substr(getenv('KIU_USERID'), 0, 3);
      $request = '<?xml version="1.0" encoding="UTF-8"?>
                  <KIU_AirBookRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="Production"
                  Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us">
                    <POS>
                      <Source AgentSine="' . $credentials->UserID . '" PseudoCityCode="' . $credentials->PseudoCityCode . '"
                      ISOCountry="'. $credentials->ISOCountry .'" ISOCurrency="'. $credentials->ISOCurrency .'" TerminalID="' . $credentials->TerminalID . '">
                        <RequestorID Type="5"/>
                        <BookingChannel Type="1"/>
                      </Source>
                    </POS>
                    <PriceInfo>
                      <FareInfos>
                        <FareInfo>
                        </FareInfo>
                      </FareInfos>
                      <PriceRequestInformation>
                        <TPA_Extensions>
                        </TPA_Extensions>
                      </PriceRequestInformation>
                    </PriceInfo>
                    ' . $this->AirItinerary() . '
                    ' . $this->DataPaxes() . '
                    <Ticketing TicketTimeLimit="24" />
                  </KIU_AirBookRQ>';
      return $request;
    }
    private function DataPaxes(){
      $paxesdetails = json_decode(Input::get('datapaxes'));
      $response = '<TravelerInfo>';
        foreach($paxesdetails as $index => $pax){
			if($pax->type == 'CHD'){
				$prefix = '<NamePrefix>JR</NamePrefix>';
				$type = '<AirTraveler PassengerTypeCode="INF">';
			}else{
				$prefix = '<NamePrefix>MR</NamePrefix>';
				$type = '<AirTraveler PassengerTypeCode="' . $pax->type . '">';
			}
          $response .= ''.$type.'
                      <PersonName>
                        '.$prefix.'
                        <GivenName>' . strtoupper($pax->firstname ). '</GivenName>
                        <Surname>' . strtoupper($pax->lastname) . '</Surname>
                      </PersonName>
                      <Email>' . $pax->email . '</Email>
					            <Document DocID="123456789" DocType="PP"></Document>
                      <TravelerRefNumber RPH="0' . ($index + 1) . '"/>
                    </AirTraveler>';
        }

      $response .= '</TravelerInfo>';
      return $response;
    }
    //
    private function AirItinerary()
    {
      //Creamos el AirItinerary incluyeno la ida y la vuelta
      $itinerary = '<AirItinerary>
            <OriginDestinationOptions>
              ' . $this->Segments($this->cache->outbound)  . '
              ' . $this->Segments($this->cache->return)  . '
            </OriginDestinationOptions>
          </AirItinerary>';
      // $itinerary = '<AirItinerary>
      // <OriginDestinationOptions>
      //   <OriginDestinationOption>
      //     <FlightSegment DepartureDateTime="2017-06-29 15:00:00"
      //     ArrivalDateTime="2017-06-29 18:30:00" FlightNumber="1996" ResBookDesigCode="Y" >
      //     <DepartureAirport LocationCode="CCS"/>
      //     <ArrivalAirport LocationCode="MIA"/>
      //     <MarketingAirline Code="K8"/>
      //     </FlightSegment>
      //   </OriginDestinationOption>
      //   <OriginDestinationOption>
      //     <FlightSegment DepartureDateTime="2017-07-01 09:30:00"
      //     ArrivalDateTime="2017-07-01 13:00:00" FlightNumber="1997" ResBookDesigCode="Y" >
      //     <DepartureAirport LocationCode="MIA"/>
      //     <ArrivalAirport LocationCode="CCS"/>
      //     <MarketingAirline Code="K8"/>
      //     </FlightSegment>
      //   </OriginDestinationOption>
      // </OriginDestinationOptions>
      // </AirItinerary>';
      return $itinerary;
    }
    private function Segments($leg)
    {
      //Construimos cada segemento con la clase correspondiente a la tarifa
      $classes = $leg->classes;
      $response = '
        <OriginDestinationOption>';
        foreach($leg->segments as $index => $segment){
          $response .= '
            <FlightSegment DepartureDateTime="' . $segment->dtime . '" ArrivalDateTime="' . $segment->atime . '"
              FlightNumber="' . $segment->flightno . '" ResBookDesigCode="' . $classes[$index] . '" >
              <DepartureAirport LocationCode="' . $segment->origin->IATA . '"/>
              <ArrivalAirport LocationCode="' . $segment->destination->IATA . '"/>
              <MarketingAirline Code="' . $segment->airline->iata . '"/>
            </FlightSegment>';
        }
        $response .= '</OriginDestinationOption>';
        return $response;
    }
}
