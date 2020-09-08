<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;
use App\Containers\UltraApi\Models\SatelliteToken;
use App\Containers\Satellite\Models\API_satellite;
use App\Containers\UltraApi\Tasks\SatelliteTokenTask;
use App\Containers\UltraApi\Tasks\UpdateSatelliteTokenTask;

/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class SatelliteEmailCurlTask extends Task{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($satellite,$data,$type)
    {
        switch ($type) {
          case 'invoice_issue':
            $route = '/api/email/itinerarie/issue';
            $response = SatelliteEmailCurlTask::CheckToken($satellite,$data,$route);
            break;
          case 'bankstransfer':
            $route = '/api/email/bankstransfer';
            $response = SatelliteEmailCurlTask::CheckToken($satellite,$data,$route);
            break;
		      case 'creditpayment':
            $route = '/api/email/creditpayment';
            $response = SatelliteEmailCurlTask::CheckToken($satellite,$data,$route);
            break;
          case 'add_freelance':
            $route = '/api/freelance/addFreelance';
            $response = SatelliteEmailCurlTask::CheckToken($satellite,$data,$route);
            break;
        }
        return $response;
    }

    public static function Curl($satellite,$data,$route){
        $response = Curl::to($satellite->domain . $route)
            ->withHeaders(SatelliteEmailCurlTask::Headers($satellite->token->access_token))
            ->withData(array('data' => json_encode($data)))
            ->post();
			dd($response);
        return $response;
    }

    public static function CheckToken($satellite,$data,$route){
      if (Carbon::now() < $satellite->token->valid_until){
        $response = SatelliteEmailCurlTask::Curl($satellite,$data,$route);
        return $response;
      }else{
        $response = (new SatelliteTokenTask())->run($satellite);
        (new UpdateSatelliteTokenTask())->run($response,$satellite);
        $satellite_updated = API_satellite::where('id',$satellite->id)->with('token')->get();
        $response = SatelliteEmailCurlTask::Curl($satellite_updated,$data,$route);
        return $response;
      }
    }

    public static function Headers($token){
        return array(
          'Authorization: Bearer ' . $token,
          'Accept: application/json'
        );
    }
}
