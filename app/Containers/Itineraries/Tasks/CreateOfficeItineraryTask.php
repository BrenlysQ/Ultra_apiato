<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Itineraries\Models\OfficeItineraryModel;

class CreateOfficeItineraryTask extends Task
{
  public function run($req)
  {
    $itinerary = new OfficeItineraryModel();
    $itinerary->localizable = $req->input('localizable');
    $itinerary->seller_email = $req->input('seller_email');
    $itinerary->currency = $req->input('currency');
    $itinerary->save();
    return $itinerary;
  }
}
