<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use Illuminate\Support\Facades\Input;
use App\Containers\Sabre\Commons\SabreCommons;
use App\Containers\Sabre\Actions\TravelOption;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class ValPayloadTask extends Task
{
    public function run()
    {
      $legs = json_decode(Input::get('legs'));
      $cache = SabreCommons::IntineraryCache($legs);
      $response = CommonActions::CreateObject();
      if(property_exists($cache,'error'))
      {
        $response = false;
      }else{

        $response->odo = new TravelOption($cache->itinerary,$cache->itinstored);
        $response->itinerary = $cache->itinerary;
        $response->currency = $cache->currency;
        $response->itinstored = $cache->itinstored;
        //$response->ticketing_limit = str_replace(' ','T',$ticketlimit->format('m-d H:00'));
      }
      return $response;
    }
}
