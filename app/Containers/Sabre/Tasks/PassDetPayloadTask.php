<?php

namespace App\Containers\Sabre\Tasks;

use DateTime;
use DateInterval;
use Carbon\Carbon;
use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class PassDetPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class PassDetPayloadTask extends Task
{
    public function run($paxdetails)
    {
      date_default_timezone_set('America/Caracas');
      $ticketlimit = Carbon::now();
      $ticketlimit->addHours(24); //FECHA LMITE ANTES DE EMITIR ESTA RESRVA (24HRS)
      $passengerrq = '
      <PassengerDetailsRQ xmlns="http://services.sabre.com/sp/pd/v3_3" version="3.3.0">
            <PostProcessing IgnoreAfter="false" RedisplayReservation="true">
                <EndTransactionRQ>
                    <EndTransaction Ind="true" />
                    <Source ReceivedFrom="PLUSULTRA TESTING" />
                </EndTransactionRQ>
            </PostProcessing>
            <PriceQuoteInfo>
                ' . $paxdetails->linkprice . '
            </PriceQuoteInfo>
            <SpecialReqDetails>
                <AddRemarkRQ>
                    <RemarkInfo>
                        <FOP_Remark Type="CASH" />
                        <Remark Type="General">
                            <Text>TEST GENERAL REMARK</Text>
                        </Remark>
                    </RemarkInfo>
                </AddRemarkRQ>
                <SpecialServiceRQ>
                    <SpecialServiceInfo>
                        ' . $paxdetails->advpax . '
                        ' . $paxdetails->secf . '
                    </SpecialServiceInfo>
                </SpecialServiceRQ>
            </SpecialReqDetails>
            <TravelItineraryAddInfoRQ>
                <AgencyInfo>
                    <Address>
                        <AddressLine>DIRECCION</AddressLine>
                        <CityName>CARACAS</CityName>
                        <CountryCode>VE</CountryCode>
                        <PostalCode>1071</PostalCode>
                        <StateCountyProv StateCode="MI" />
                        <StreetNmbr>OFIC 18</StreetNmbr>
                        <VendorPrefs>
                            <Airline Hosted="true" />
                        </VendorPrefs>
                    </Address>
                    <Ticketing PseudoCityCode="4Q0H" ShortText="TEST" TicketTimeLimit="' . $ticketlimit->format('m-d') . 'T' . $ticketlimit->format('H:00') . '" TicketType="7TAW"/>
                </AgencyInfo>
                <CustomerInfo>
                    <ContactNumbers>
                        ' . $paxdetails->contact_numbers . '
                    </ContactNumbers>
                    ' . $paxdetails->emails . '
                    ' . $paxdetails->cinfopax . '
                </CustomerInfo>
            </TravelItineraryAddInfoRQ>
      </PassengerDetailsRQ>';
      return $passengerrq;
    }
}
