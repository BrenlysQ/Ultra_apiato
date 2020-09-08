<?php

namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use App\Containers\Sabre\Actions\SabreAuthSoap;
use App\Containers\Sabre\Tasks\AltDaysRequestTask;
use App\Containers\Sabre\Tasks\AltDaysParseTask;
use App\Containers\Sabre\Tasks\AltDateCompressTask;
use App\Containers\Sabre\Tasks\AltDatePayloadTask;
use Illuminate\Support\Facades\Cache;
use App\Commons\TagsGdsHandler;
use App\Commons\CommonActions;
class AlternateDays extends Action{
  private static $token;
  public function run(){
    set_time_limit(0);
    $payload_client = (object) array(
      "trip" => Input::get('trip',1),
      "departure_city" => Input::get('departure_city','PTY'),
      "destination_city" => Input::get('destination_city','MAD'),
      "departure_date" => CommonActions::FormatDate(Input::get('departure_date','2018-01-20')),
      "return_date" => CommonActions::FormatDate(Input::get('return_date','2018-01-27')),
      "adult_count" => Input::get('adult_count',1),
      "child_count" => Input::get('child_count',0),
      "inf_count" => Input::get('inf_count',0),
      "cabin" => Input::get('cabin','Y'),
      "currency" => Input::get('currency',1),
      "se" => 4
    );
    $payload = $this->call(AltDatePayloadTask::class,[$payload_client]);
    $response = $this->call(AltDaysRequestTask::class,[$payload]);
    $compressed_response = $this->call(AltDateCompressTask::class,[$response]);
    $parsed_response = $this->call(AltDaysParseTask::class,[$compressed_response,$payload_client]);
    $parsed_response->from_sabre = $response;
    $parsed_response->tagpu = $this->cache($payload_client,$response);
    return $parsed_response;
  }
  private function cache($payload,$response){
    $identifier = '3DAYS-' . str_random(25);
    Cache::put($identifier, json_encode($response), 25);
    return TagsGdsHandler::StoreTag($identifier, $payload);
  }
}
