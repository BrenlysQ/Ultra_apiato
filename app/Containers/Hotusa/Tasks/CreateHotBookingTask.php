<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

/**
 * Class UpdateUserTask.
 *
 * @author Plusultra, C.A.
 */
class CreateHotBookingTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($divisa)
    {

      $data = CommonActions::CreateObject();

      if ($divisa == 'DO') {
        $data->currency = 4;
        $data->currencies = 'USD';
      }else{
        $data->currency = 3;
        $data->currencies = 'VEF';
      }
      $data->usersatdata = Input::get('usersat');
      $data->usersatid = (json_decode(Input::get('usersat')))->id;
      $data->satelite = Auth::id();
      $data->pgateway = Input::get('pgateway',1);

      return $data;
    }

}
