<?php

namespace App\Containers\Sabre\Tasks;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Ship\Parents\Tasks\Task;
use Carbon\Carbon;
use App\Containers\Sabre\Tasks\BfmPayloadTask;
use App\Containers\Sabre\Commons\SabreCommons;
/**
 * Class PaxPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AltDatePayloadTask extends Task
{

    public function run($payload_client)
    {
      //print_r($payload_client); die;
      $currency = CurrenciesHandler::GetbyId($payload_client->currency);
      $purchasedate = Carbon::now();
      $purchasedate->addHours(24);
      $paxes = '';
      //echo SabreCommons::RequestPayloadPax($payload_client,false); die;
      // $paxes .= BfmPayloadTask::RequestPaxes($payload_client->adult_count,"ADT");
      // $paxes .= BfmPayloadTask::RequestPaxes($payload_client->child_count,"C07");
      // $paxes .= BfmPayloadTask::RequestPaxes($payload_client->inf_count,"INF");
      $paxes = substr($paxes, 0, -1);
      print_r($paxes);
      $alt_days = '<OTA_AirLowFareSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05" ResponseType="OTA" ResponseVersion="3.3.0" Version="3.3.0">
                  <POS>
                      <Source PseudoCityCode="3N9H">
                        <RequestorID ID="'.getenv('SABRE_USERID').'" Type="1">
                            <CompanyName Code="TN"/>
                        </RequestorID>
                      </Source>
                  </POS>
                  <OriginDestinationInformation RPH="1">
                    <DepartureDateTime>' . $payload_client->departure_date . 'T00:00:00</DepartureDateTime>
                    <OriginLocation LocationCode="' . $payload_client->departure_city . '" LocationType="A"/>
                    <DestinationLocation LocationCode="' . $payload_client->destination_city . '" LocationType="A"/>
                    <TPA_Extensions>
                        <SegmentType Code="O"/>
                    </TPA_Extensions>
                  </OriginDestinationInformation>
                  <OriginDestinationInformation RPH="2">
                    <DepartureDateTime>' . $payload_client->return_date . 'T00:00:00</DepartureDateTime>
                    <OriginLocation LocationCode="' . $payload_client->destination_city . '" LocationType="A"/>
                    <DestinationLocation LocationCode="' . $payload_client->departure_city . '" LocationType="A"/>
                    <TPA_Extensions>
                        <SegmentType Code="O"/>
                    </TPA_Extensions>
                  </OriginDestinationInformation>
                  <TravelPreferences ValidInterlineTicket="true">
                    <CabinPref PreferLevel="Preferred" Cabin="Economy"/>
                    <TPA_Extensions>
                      <NumTrips PerDateMax="5" PerDateMin="1"/>
                    </TPA_Extensions>
                  </TravelPreferences>
                  <TravelerInfoSummary>
                    <SeatsRequested>1</SeatsRequested>
                      <AirTravelerAvail>
                          ' . SabreCommons::RequestPayloadPax($payload_client,false) . '
                      </AirTravelerAvail>
                      <PriceRequestInformation CurrencyCode="' . $currency->code . '" ProcessThruFaresOnly="false" PurchaseDate="' . $purchasedate->format('Y-m-d') . '">
                      </PriceRequestInformation>
                  </TravelerInfoSummary>
                  <TPA_Extensions>
                      <IntelliSellTransaction>
                          <RequestType Name="AD3" />
                          <CompressResponse Value="false" />
                      </IntelliSellTransaction>
                  </TPA_Extensions>
              </OTA_AirLowFareSearchRQ>';
      return $alt_days;
    }
}
