<?php

namespace App\Containers\HilbeToday\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\HilbeToday\Models\DollarPrice;
use Carbon\Carbon;


/**
 * Class UpdateUserTask.
 *
 * @author Mahmoud Zalt <mahmoud@zalt.me>
 */
class ShowDollarPriceTask extends Task
{
    public function run() {
        $latest_dollar = DollarPrice::select('hilbe_price','dt_price', 'price_bsf','percentage', 'timestamp')->latest()->first();

        return $latest_dollar;
    }
}