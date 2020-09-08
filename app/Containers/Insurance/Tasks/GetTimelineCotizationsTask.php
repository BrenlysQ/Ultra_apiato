<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Insurance\Models\VoucherModel;
use Carbon\Carbon;

class GetTimelineCotizationsTask extends Task
{
    public function run()
    {
       set_time_limit(0);
		$total = [];
		$key = 0;
        $dt = Carbon::create(2018, 1, 1, 0);
		$month = $dt;
        $endyear = Carbon::now();		
        while ($dt->diffInMonths($endyear) > 0) {
            $count = VoucherModel::whereBetween('created_at', [$dt->toDateString(), $dt->addMonth()->toDateString()])
                                    ->count();
			$month = $month->shortEnglishMonth;
            $total[$key] = array( $month, $count);
			$month = $dt;
			$key++;
        }
        return $total;
    }
}
