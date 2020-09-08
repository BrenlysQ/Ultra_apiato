<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use App\Containers\UltraApi\Models\SatelliteToken;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class SatelliteTokenTask extends Task{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($satellite)
    {
      $data = array( 
            'grant_type' => 'password',
            'client_id' => $satellite->client_id,
            'client_secret' => $satellite->client_secret,
            'username' => 'plusultradesarrollo@gmail.com',
            'password' => 'viajaresmuybueno',
            'scope' => ''
          );

      $response = Curl::to($satellite->domain.'/oauth/token')
          ->withHeaders($this->headers())
          ->withData($data)
          ->asJson()
          ->withTimeout(0)
          ->post();

      return $response;

    }

    public static function headers(){
      return array(
        'Content-Type: application/x-www-form-urlencoded'
      );
    }

}
