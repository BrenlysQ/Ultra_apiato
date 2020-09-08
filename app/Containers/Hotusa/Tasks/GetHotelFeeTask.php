<?php

namespace App\Containers\Hotusa\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Commons\CommonActions;
/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class GetHotelFeeTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run()
    {
      //fee %
      $fee = CommonActions::CreateObject();
      $fee->name = 'Fee';
      $fee->type = 2;
      $fee->fee = 10;
      $feeret[0] = $fee;
      return $feeret;
    }

}
