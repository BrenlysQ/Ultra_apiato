<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Insurance\Tasks\GetCountryByIataTask;
use Carbon\Carbon;

class GetPricePayload extends Task
{
    public function run($itinerary, $dob)
    {
      $child = 0;
      $adult = 0;
      $oldman = 0;
      foreach ($dob as $date) {
        //Pasar a commonAction de fomra que retorne el ageid EJ; adultos, menor, tercera-edad
        // y utilizar esa respuesta en EmitsCotTask para determinar el ageid
        $parsedate = Carbon::parse($date);
        $difference = Carbon::now()->diffInYears($parsedate);
          if ($difference < 22) {
            $child++;
          }elseif ($difference > 22 and $difference < 75){
            $adult++;
          }else {
            $oldman++;
        }
      }
      $departure_country = (new GetCountryByIataTask())->run($itinerary->departure_city);
      $destination_country = (new GetCountryByIataTask())->run($itinerary->destination_city);
      $data = array('body'=>'
      {
        "item": {
          "edad": {
            "adulto": '.$adult.',
            "menor": '.$child.',
            "tercera-edad": '.$oldman.'
          },
          "countryini": "'. $departure_country .'",
          "countryend": "'. $destination_country .'",
          "dateini": "'.$itinerary->departure_date.'",
          "dateend": "'.$itinerary->return_date.'"
        }
      }');
      return $data;
    }

}
