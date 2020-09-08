<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Invoice\Models\ItemsModel; 
use App\Containers\Insurance\Models\VoucherModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;

class GetPaxesTypeCountTask extends Task
{
    public function run()
    {
        $vouchers = VoucherModel::all();
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
        $response = array(
		0 => array('Adultos', $adults),
		1 => array('Ninos',$childs),
		2 => array('Ancianos', $oldman)
		);
        return $response;
    }
}
