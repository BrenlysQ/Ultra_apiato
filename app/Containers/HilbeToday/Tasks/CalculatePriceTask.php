<?php

namespace App\Containers\HilbeToday\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\HilbeToday\Models\HtConfig;


/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class CalculatePriceTask extends Task
{
    public function run($price) {

    $config = HtConfig::latest()->first();
        $percentage_usd = $config->percentage_hilbe;
        $percentage_bsf = $config->percentage_bsf;
        $price_usd  = $price * (1 + $percentage_usd);
        $price_bsf  = $price * (1 - $percentage_bsf);
        $response   = (object) array(
            'percentage_usd' => $percentage_usd,
            'percentage_bsf' => $percentage_bsf,
            'price_usd'      => $price_usd,
            'price_bsf'      => $price_bsf
        );

    return $response;
    }
}