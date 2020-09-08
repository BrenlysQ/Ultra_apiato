<?php

namespace App\Containers\HilbeToday\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\HilbeToday\Models\HtConfig;


/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CalculateDifferenceTask extends Task
{
    public function run($latest, $current) {

    $config = HtConfig::latest()->first();
    $percentage_notice = $config->percentage_notice;
    if($current > $latest){
        if(($current - $latest) > $latest*$percentage_notice){
            $response = true;
        }else{
            $response = false;
        }
    }else{
        if(($latest - $current) > $latest*$percentage_notice){
            $response = true;
        }else{
            $response = false;
        }
    }

    return $response;
    }
}