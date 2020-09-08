<?php

namespace App\Containers\HilbeToday\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\HilbeToday\Models\DollarPrice;
use App\Containers\HilbeToday\Models\HtConfig;
use App\Containers\HilbeToday\Tasks\StorePriceTask;

/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class ManagePriceTask extends Task
{
    public function run($price_object) {
        
        $lastest_dollar = DollarPrice::latest()->first();
        $lastest_dollar = $lastest_dollar->dt_price;
        $current_dollar = $price_object->USD->dolartoday;
        $condition = ManagePriceTask::calculateDifference($lastest_dollar, $current_dollar);
        if($condition){
            $req = ManagePriceTask::calculatePrice($current_dollar);
            $dollar = (new StorePriceTask())->run($req, $price_object);
        }else{
            $dollar = DollarPrice::latest()->first();
        }

        return $dollar;
    }
    public function calculateDifference ($latest, $current){

        $response = (new CalculateDifferenceTask())->run($latest, $current);

        return $response;
    }
    public function calculatePrice ($price){

        $response = (new CalculatePriceTask())->run($price);

        return $response;
    }
}