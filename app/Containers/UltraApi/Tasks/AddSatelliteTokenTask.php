<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use App\Containers\Satellite\Models\API_satellite;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class AddSatelliteTokenTask extends Task{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($response,$satellite)
    {
      $expires_in = $response->expires_in;
      $dt = Carbon::now();
      $dt = $dt->addSeconds($expires_in);
      $valid_until = json_encode($dt);
      $aux = json_decode($valid_until);

      $sat_to_create = API_satellite::where('domain',$satellite->domain)->get();
      $token_sat = API_satellite::findOrFail($sat_to_create[0]->id);        
      $token_sat->token()->create([
         'access_token' => $response->access_token,
         'refresh_token' => $response->refresh_token,
         'valid_until' => $aux->date
      ]);  

    }

}