<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Carbon\Carbon;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;


class GetPartnerChartTask extends Task
{
    public function run($req)
    {
      set_time_limit(0);
      $partner = $req->input('id');
      $currency = $req->input('currency');
      $total = array();
      $year = $req->input('year');
      $dt = Carbon::create($year, 1, 1, 0);
      if (Carbon::now()->diffInYears(Carbon::parse($year)) == 0) {
        $endyear = Carbon::now();
      }else {
        $endyear = Carbon::create(($year+1), 1, 1, 0);
      }
      while ($dt->diffInWeeks($endyear) > 2) {
        $count = InvoiceModel::where('usersatid', $partner)
                              ->where('currency', $currency)
                              ->whereBetween('created_at', [$dt->toDateString(), $dt->addWeeks(2)->toDateString()])
                              ->count();
		    $total[$dt->toDateTimeString()] = $count;
      }
      return $total;
    }
}
