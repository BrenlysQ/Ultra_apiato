<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\OfficeItineraryModel;

class GetOfficeItinerariesTask extends Task
{
  public function run($req)
  {
    $itineraries = OfficeItineraryModel::with('status')->get();
    return $itineraries;
  }
}
