<?php

namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Commons\CommonActions;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuParseResponse;
use App\Containers\Kiu\Models\FaresCacheModel;
use App\Containers\Kiu\Models\KiuRoutesModel;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\Kiu\Tasks\AirAvailPayloadTask;
use App\Containers\Kiu\Tasks\MakeRequestTask;
use Carbon\Carbon;
use App\Commons\Logger;
use App\Containers\Kiu\Tasks\AirPriceTask;
use App\Containers\Kiu\Actions\TOptionsHandler;
class FlightsHandler extends Action {
  public static function GetFlights() {
    set_time_limit(0);
    $payload = (object) array(
		  "trip" => Input::get('trip',1),
		  "departure_city" => Input::get('departure_city','CCS'),
		  "destination_city" => Input::get('destination_city','PTY'),
		  "departure_date" => CommonActions::FormatDate(Input::get('departure_date','2017-06-01')),
		  "return_date" => CommonActions::FormatDate(Input::get('return_date','2017-06-08')),
		  "adult_count" => Input::get('adult_count',1),
		  "child_count" => Input::get('child_count',0),
		  "inf_count" => Input::get('inf_count',0),
		  "cabin" => Input::get('cabin','Economy'),
		  "currency" => Input::get('currency',3),
		  "passenger_type" => 'ADT',
		  "se" => 2,
		  "direct" => 'false'
		);
    $request = (new AirAvailPayloadTask())->run($payload);
    //print_r($request);
    $response = (new MakeRequestTask())->run($request);
    //return json_encode($response);

    //$response = file_get_contents(app_path('Containers/Kiu/Consultas/VLNMIA.json'));
    $opts = (new AirPriceTask())->run($response, $payload, false);
    //print_r($opts); die;
    if(!is_object($opts)){
      $opts = (new TOptionsHandler())->GetOpts($payload);
      $opts->debug = $response;
    }
	$opts->debug = $response;
    return json_encode($opts);
  }
}
