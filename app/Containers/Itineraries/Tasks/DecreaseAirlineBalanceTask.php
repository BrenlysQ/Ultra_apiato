<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\AirlineBalanceModel;

class DecreaseAirlineBalanceTask extends Task
{
  public function run($amount, $id)
  {
    $airline = AirlineBalanceModel::where('airline_code', $id)->first();
    $airline->balance = $airline->balance - $amount;
    $airline->update();
    return $airline;
  }
}
