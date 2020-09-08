<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\AirlineBalanceModel;

class UpdateAirlineBalanceTask extends Task
{
  public function run($req)
  {
    $airline = AirlineBalanceModel::where('airline_code', $req->input('airline_code'))->first();
    $airline->balance = $req->input('balance');
    $airline->update();
    return $airline;
  }
}
