<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\OfficeItineraryModel;

class GetOfficeUserItinerariesTask extends Task
{
  public function run($req)
  {
    $itineraries = OfficeItineraryModel::where('seller_email', $req->input('seller_email'))->with('status')->get();
    return $itineraries;
  }
}
