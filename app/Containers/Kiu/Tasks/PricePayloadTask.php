<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Kiu\Tasks\MakeRequestTask;
use App\Containers\Kiu\Tasks\CredentialsTaks;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class PricePayloadTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($outpl, $retpl, $currency = 3, $ptype = ' ADT')
    {
      $TimeStamp = date("c");
      $credentials = (new CredentialsTaks())->run($currency);
      $PseudoCityCode = $credentials->PseudoCityCode;
      $ISOCountry = $credentials->ISOCountry;
      $cur = CurrenciesHandler::GetCurrency($currency);
      $ISOCurrency = $cur->code;
      $PassengerTypeCode = $ptype;
      $PassengerTypeQuantity = '1';
      $request = '<?xml version="1.0" encoding="UTF-8"?>
                  <KIU_AirPriceRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="'.getenv('KIU_TARGET').'" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en-us">
                     <POS>
                        <Source AgentSine="'.$credentials->UserID.'" PseudoCityCode="'.$PseudoCityCode.'" ISOCountry="'.$ISOCountry.'" ISOCurrency="'.$ISOCurrency.'" TerminalID="'.$credentials->TerminalID.'"></Source>
                     </POS>
                    <AirItinerary>
                      <OriginDestinationOptions>
                        <OriginDestinationOption>
                          ' . $outpl . '
                        </OriginDestinationOption>
                        <OriginDestinationOption>
                          ' . $retpl . '
                        </OriginDestinationOption>
                      </OriginDestinationOptions>
                    </AirItinerary>
                    <TravelerInfoSummary>
                      <AirTravelerAvail>
                        <PassengerTypeQuantity Code="'.$PassengerTypeCode.'" Quantity="'.$PassengerTypeQuantity.'"/>
                      </AirTravelerAvail>
                    </TravelerInfoSummary>
                  </KIU_AirPriceRQ>';
      return $request;
    }

}
