<?php

namespace App\Containers\Sabre\Tasks;

use App\Ship\Parents\Tasks\Task;
/**
 * Class PaxPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AlternateDaysPayloadTask extends Task
{

    public function run($payload_client)
    {
      $alt_days = '<OTA_AirLowFareSearchRQ xmlns="http://www.opentravel.org/OTA/2003/05" AvailableFlightsOnly="true" Version="3.3.0">
                  <POS>
                      <Source PseudoCityCode="'.getenv('SABRE_GROUP').'">
                        <RequestorID ID="'.getenv('SABRE_USERID').'" Type="1">
                            <CompanyName Code="TN" CodeContext="Context" />
                        </RequestorID>
                      </Source>
                  </POS>
                  <OriginDestinationInformation>
                      <DepartureDateTime>' . $payload_client->departure_date . 'T00:00:00</DepartureDateTime>
                      <OriginLocation LocationCode="' . $payload_client->departure_city . '" />
                      <DestinationLocation LocationCode="' . $payload_client->destination_city . '" />
                      <TPA_Extensions>
                          <ConnectionTime Max="0" />
                      </TPA_Extensions>
                  </OriginDestinationInformation>
                  <OriginDestinationInformation>
                      <DepartureDateTime>' . $payload_client->return_date . 'T00:00:00</DepartureDateTime>
                      <OriginLocation LocationCode="' . $payload_client->destination_city . '" />
                      <DestinationLocation LocationCode="' . $payload_client->departure_city . '" />
                      <TPA_Extensions>
                          <ConnectionTime Max="0" />
                      </TPA_Extensions>
                  </OriginDestinationInformation>
                  <TravelPreferences ValidInterlineTicket="true">
                      <TPA_Extensions>
                          <InterlineIndicator Ind="true" />
                      </TPA_Extensions>
                  </TravelPreferences>
                  <TravelerInfoSummary>
                      <AirTravelerAvail>
                          <PassengerTypeQuantity Code="ADT" Quantity="1" />
                      </AirTravelerAvail>
                      <PriceRequestInformation CurrencyCode="USD">
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


