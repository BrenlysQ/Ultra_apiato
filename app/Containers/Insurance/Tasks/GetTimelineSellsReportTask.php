<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Insurance\Models\VoucherModel;
use Carbon\Carbon;

class GetTimelineSellsReportTask extends Task
{
    public function run($req)
    {
        set_time_limit(0);
        $key = 0;
        $dt = Carbon::create(2018, 1, 1, 0);
        $month = $dt;
        $endyear = Carbon::now();
        if($req->input('request_type') == 'full'){
            $vouchers = VoucherModel::get();
            while ($dt->diffInMonths($endyear) > 0) {
                $count = VoucherModel::whereBetween('created_at', [$dt->toDateString(), $dt->addMonth()->toDateString()])
                                        ->count();
                $month = $month->shortEnglishMonth;
                $total[$key] = array( $month, $count);
                $month = $dt;
                $key++;
            }
        }else{
            $dti = Carbon::parse($req->input('dti'));
            $dte = Carbon::parse($req->input('dte'));
            $vouchers = VoucherModel::whereBetween('created_at', [$dti->toDateString(), $dte->toDateString()])
                                    ->get();
            $key = 0;
            $tag = $dti;
            if ($dti->diffInDays($dte) > 0 && $dti->diffInDays($dte) <= 30) {
                while ($dti->diffInDays($dte) > 0) {
                    $count = VoucherModel::whereBetween('created_at', [$dti->toDateString(), $dti->addDay()->toDateString()])
                                        ->count();
                    $tag = $tag->shortEnglishDayOfWeek;
                    $total[$key] = array($tag, $count);
                    $tag = $dti;
                    $key++;
                }
            } elseif($dti->diffInWeeks($dte) > 0 && $dti->diffInWeeks($dte) <= 7) {
                while ($dti->diffInWeeks($dte) > 0) {
                    $count = VoucherModel::whereBetween('created_at', [$dti->toDateString(), $dti->addWeek()->toDateString()])
                                        ->count();
                    $tag = 'Semana '.$key+1;
                    $total[$key] = array($tag, $count);
                    $key++;
                }
            }elseif($dti->diffInMonths($dte) > 1){
                while ($dti->diffInMonths($dte) > 0) {                 
                    $count = VoucherModel::whereBetween('created_at', [$dti->toDateString(), $dti->addMonth()->toDateString()])
                                        ->count();
                    $tag = $tag->shortEnglishMonth;
                    $total[$key] = array( $tag, $count);
                    $tag = $dt;
                    $key++;
                }
            }
        }
        $vouchers->tograph = $total;
        return $vouchers;
    }
}
