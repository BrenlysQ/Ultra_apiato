<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Containers\Hotusa\Models\HotusaProvincies;
use Ixudra\Curl\Facades\Curl;
use App\Commons\CommonActions;
/**
 * Class UpdateUserTask.
 *
 */
class GetAirport extends Task
{

    /**
     */
    public function run($payload)
    {
      $province = json_decode(HotusaProvincies::where('province_code',$payload->provincia)
                  ->with('country')->first()->toJson());
      // $province->province_name;
      // $province->country->country_name;
      $url = 'https://maps.googleapis.com/maps/api/geocode/json?address=Airport+' .
      urlencode($province->province_name) . '+' . urlencode($province->country->country_name) .
      '&key=AIzaSyADuVaZNfd0ERsCzkZCtm7bEPpHFT3iKBY';
      $response = Curl::to($url)
        ->asJsonResponse()
        ->get();
      //dd($response);
	  //print_r($response); die;
      $aux = $response->results[0];
      $airport = CommonActions::CreateObject();
      //print_r($aux); die;
      $airport->name = $aux->address_components[0]->long_name;
      $airport->lat = $aux->geometry->location->lat;
      $airport->lon = $aux->geometry->location->lng;
      return $airport;
    }

}
