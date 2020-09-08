<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class AirAvailPayloadTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($payload)
    {
      $TimeStamp = date("c");
      $credentials = (new CredentialsTaks())->run($payload->currency);
	  ($payload->cabin == 'Nopreference') ? $cabin = '' : $cabin = $payload->cabin;
	  ($payload->currency == 4) ? $iso = 'ISOCountry="PA"' : $iso = 'ISOCountry="VE"';
      $request='<?xml version="1.0" encoding="UTF-8"?>
                <KIU_AirAvailRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="'.getenv('KIU_TARGET').'" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us" DirectFlightsOnly="'.$payload->direct.'">
                   <POS>
                      <Source AgentSine="'. $credentials->UserID .'" TerminalID="'. $credentials->TerminalID .'" ' . $iso . ' />
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
                      <CabinPref Cabin="'. $cabin .'" />
                   </TravelPreferences>
                   <TravelerInfoSummary>
                      <AirTravelerAvail>
                        <PassengerTypeQuantity Code="ADT" Quantity="'.$payload->adult_count.'" />
                      </AirTravelerAvail>
                   </TravelerInfoSummary>
                </KIU_AirAvailRQ>';
      return $request;
    }

}
