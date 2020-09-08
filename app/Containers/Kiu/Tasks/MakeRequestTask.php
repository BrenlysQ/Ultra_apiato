<?php

namespace App\Containers\Kiu\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Ixudra\Curl\Facades\Curl;
use App\Commons\CommonActions;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class MakeRequestTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($request, $json = true) {
      $Target = 'Production'; //Testing ohf8chiZjahKooz0 or Production ohch5aeJchiH3fac
      $password = 'ohch5aeJchiH3fac';
      //Web Services URL List
      $SERVERS = 'https://ssl00.kiusys.com/ws3/';
                      /*'https://n10.kiusys.com/ws3/',
                      'https://n30.kiusys.com/ws3/',
                      'https://n40.kiusys.com/ws3/',
                     'https://webservices-us.kiusys.com/ws3/');*/
      $response = Curl::to(getenv('KIU_ENVIRONMENT'))
        ->withData( array( 'user' => getenv('KIU_USER'),'password' => $password,'request' => $request ) )
        ->post();
      if($json) {
        $response = CommonActions::XML2JSON($response);
        return json_decode($response);
      } else {
        return $response;
      }
    }

}
