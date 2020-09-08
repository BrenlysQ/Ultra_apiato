<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\AirlineBalanceModel;

class CreateAirlineBalanceHistoryTask extends Task
{
  public function run($itinerary)
  {
    $airline = AirlineBalanceModel::where('airline_code', $itinerary->CompanyShortName)
                                  ->where('currency', $itinerary->currency)->first();
    $history->localizable = $itinerary->id;
    $history->airline_code = $itinerary->CompanyShortName;
    $history->last_balance = $airline->balance;
    $history->balance = $airline->balance - $itinerary->TotalAmount;
    $history->currency = $itinerary->currency;
    $history->save();
    return $history;
  }
}
