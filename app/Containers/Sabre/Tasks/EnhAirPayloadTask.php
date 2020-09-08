<?php

namespace App\Containers\Sabre\Tasks;


use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class EnhAirPayloadTask extends Task
{
    public function run($pricingtypes,$segmentsdata)
    {
      $enhancedrq = '
      <EnhancedAirBookRQ xmlns="http://services.sabre.com/sp/eab/v3_7" version="3.7.0" HaltOnError="false">
        <OTA_AirBookRQ>
          <RetryRebook Option="true"/>
          <HaltOnStatus Code="UC"/>
          <HaltOnStatus Code="US"/>
          <HaltOnStatus Code="NN"/>
          <HaltOnStatus Code="NO"/>
          <OriginDestinationInformation>
            ' . $segmentsdata . '
          </OriginDestinationInformation>
          <RedisplayReservation NumAttempts="5" WaitInterval="2000"/>
        </OTA_AirBookRQ>
        <OTA_AirPriceRQ>
          <PriceRequestInformation Retain="true">
            <OptionalQualifiers>
              <PricingQualifiers>
                ' . $pricingtypes . '
              </PricingQualifiers>
            </OptionalQualifiers>
          </PriceRequestInformation>
        </OTA_AirPriceRQ>
        <PostProcessing IgnoreAfter="false">
          <RedisplayReservation WaitInterval="500"/>
        </PostProcessing>
        <PreProcessing IgnoreBefore="false"/>
      </EnhancedAirBookRQ>';
      return $enhancedrq;
    }
}
