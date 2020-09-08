<?php

namespace App\Containers\HilbeToday\Tasks;

use App\Ship\Parents\Tasks\Task;
use Ixudra\Curl\Facades\Curl;
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
    public function run() {
        $response = Curl::to('https://s3.amazonaws.com/dolartoday/data.json')
        ->withTimeout(0)
        ->get();
        $response = utf8_encode($response);

        return json_decode($response);
    }
}