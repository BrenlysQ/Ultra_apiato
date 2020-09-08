<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Insurance\Models\VoucherModel;
use Carbon\Carbon;

class GetPaxTypeReportTask extends Task
{
    public function run($request)
    {
        set_time_limit(0);
        if($req->input('request_type') == 'full'){
            $vouchers = VoucherModel::get();
            $vouchers->type = 'full';
        }else{
            $dti = Carbon::parse($req->input('dti'));
            $dte = Carbon::parse($req->input('dte'));
            $vouchers = VoucherModel::whereBetween('created_at', [$dti->toDateString(), $dte->toDateString()])
            ->get();  
            $vouchers->type = 'detail';
        }
        $adults = 0;
        $childs = 0;
        $oldman = 0;
        foreach ($vouchers as $key => $voucher) {
            if (isset($voucher->response->adults) && $voucher->response->adults > 0) {
                $adults += $voucher->response->adults;
            } elseif (isset($voucher->response->childs) && $voucher->response->childs > 0) {
                $childs += $voucher->response->childs;
            }elseif (isset($voucher->response->oldmans) && $voucher->response->oldmans > 0){
                $oldman += $voucher->response->oldmans;
            }
        }
        $vouchers->tograph = array(
		0 => array('Adultos', $adults),
		1 => array('Ninos',$childs),
		2 => array('Ancianos', $oldman)
        );
        return $vouchers;
    }
}
