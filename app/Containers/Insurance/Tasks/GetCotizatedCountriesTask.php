<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Invoice\Models\ItemsModel; 
use App\Containers\Insurance\Models\VoucherModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;

class GetCotizatedCountries extends Task
{
    public function run($field)
    {
        set_time_limit(0);
        $vouchers = VoucherModel::distinct($field)
                                ->count();
        /*foreach ($vouchers as $key => $voucher) {
            $count = VoucherModel::where('destination_city', $voucher->destination_citt);
        }*/
        return $vouchers;
    }
}
