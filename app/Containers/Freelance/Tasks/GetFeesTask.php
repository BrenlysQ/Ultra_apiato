<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Carbon\Carbon;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;


class GetFeesTask extends Task
{
    public function run($req)
    {
      set_time_limit(0);
      $satellite = $req->input('satellite');
      $freelance = FreelanceModel::where('email', $satellite)->first();
      $total = array();
      $year = $req->input('year');
      $dt = Carbon::create($year, 1, 1, 0);
      if (Carbon::now()->diffInYears(Carbon::parse($year)) == 0) {
        $endyear = Carbon::now();
      }else {
        $endyear = Carbon::create(($year+1), 1, 1, 0);
      }
      while ($dt->diffInWeeks($endyear) > 2) {
        $sum = InvoiceModel::where('id_freelance', $freelance->id)
                              ->whereBetween('created_at', [$dt->toDateString(), $dt->addWeeks(2)->toDateString()])
                              ->sum('total_fee');
		    $total[$dt->toDateTimeString()] = $sum;
      }
      return $total;
    }
}
