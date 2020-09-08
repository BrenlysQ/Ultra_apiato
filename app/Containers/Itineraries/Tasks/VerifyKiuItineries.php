<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Tasks\CredentialsTaks;
use Carbon\Carbon;


class VerifyKiuItineries extends Task
{
  public function run($itinerary, $currency)
  {
    $TimeStamp = date("c");
    $credentials = (new CredentialsTaks())->run($currency);
    $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <KIU_TravelItineraryReadRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="Production" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us">
              <POS>
                <Source AgentSine="' . $credentials->UserID . '" TerminalID="' . $credentials->TerminalID . '" ISOCountry="'. $credentials->ISOCountry .'" />
              </POS>
              <UniqueID Type="14" ID="'.$itinerary.'" />
            </KIU_TravelItineraryReadRQ>';
	  return $xml;
  }
}
