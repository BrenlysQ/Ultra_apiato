<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Insurance\Tasks\GetCountryByIataTask;
use Carbon\Carbon;
use Illuminate\Support\Facades\Input;

class GetQuoteTask extends Task
{
    public function run()
    {
      $origin = Input::get('o');
	  $destination = Input::get('d');
	  $adults = Input::get('a');
	  $childs = Input::get('c');
	  $olds = Input::get('v');
	  $destination = Input::get('d');
	  $date_out = Carbon::parse(Input::get('fi'));
	  $date_in = Carbon::parse(Input::get('fo'));
      $departure_country = (new GetCountryByIataTask())->run($origin);
      $destination_country = (new GetCountryByIataTask())->run($destination);
      $data = array('body'=>'
      {
        "item": {
          "edad": {
            "adulto": '.$adults.',
            "menor": '.$childs.',
            "tercera-edad": '.$olds.'
          },
          "countryini": "'. $departure_country .'",
          "countryend": "'. $destination_country .'",
          "dateini": "'. $date_out->format('Y-m-d') .'",
          "dateend": "'. $date_in->format('Y-m-d') .'"
        }
      }');
      return $data;
    }

}
