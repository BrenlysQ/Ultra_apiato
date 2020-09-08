<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\AirlineBalanceModel;

class CreateAirlineBalanceTask extends Task
{
  public function run($req)
  {
    $airline = new AirlineBalanceModel();
    $airline->airline_code = $req->input('airline_code');
    $airline->airline_name = $req->input('airline_name');
    $airline->balance = $req->input('balance');
    $airline->save();
    return $airline;
  }
}
