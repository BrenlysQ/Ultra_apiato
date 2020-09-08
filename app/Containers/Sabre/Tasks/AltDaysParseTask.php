<?php

namespace App\Containers\Sabre\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Commons\CommonActions;
use App\Containers\Sabre\Data;
use App\Containers\Sabre\Tasks\FligthExistTask;
use App\Containers\Sabre\Data\AltDateOption;
use Carbon\Carbon;
/**
 * Class AltDaysParseTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AltDaysParseTask extends Task
{
    public $compressed;
    public function run($response,$payload_client)
    {
      $parsed_response = CommonActions::CreateObject();
      $parsed_response->dates = array();
      $parsed_response->response = $response;
        //dd($payload_client);
      $parsed_response->outbound = Carbon::parse($payload_client->departure_date)->subDays(3);
      $parsed_response->return = Carbon::parse($payload_client->return_date)->subDays(3);
        $this->compressed = $response;
        //print_r($this->compressed);
        //$departure_date =  Carbon::parse($payload_client->departure_date)->subDays(3);
        //$return_date = Carbon::parse($payload_client->return_date)->subDays(3);
        for($i = 0; $i < 7 ; $i++){
            $return_date = Carbon::parse($payload_client->return_date)->subDays(3);
            $idate = $return_date->addDays($i);
            for($j = 0; $j < 7 ; $j++){
                $departure_date =  Carbon::parse($payload_client->departure_date)->subDays(3);
                $jdate = $departure_date->addDays($j);
                $option = $this->FligthExist($idate,$jdate);
                $parsed_response->dates[$i][$j] = new AltDateOption($option,$jdate,$idate,$payload_client);
            }
        }
        return $parsed_response;
        //return $compressed_response;
    }
    public function FligthExist($date_return, $date_departure)
    {
        //Carbon::parse('first day of December 2008');
        $time_departure = $date_departure->timestamp;
        $time_return = $date_return->timestamp;
        if(isset($this->compressed[(string)$time_return][(string)$time_departure])){
            return $this->compressed[(string)$time_return][(string)$time_departure];
        }
        return false;

        return $compressed_response;
    }
}
