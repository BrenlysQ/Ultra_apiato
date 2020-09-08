<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use Carbon\Carbon;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;


class ComparePartnersTask extends Task
{
    public function run($req)
    {
      set_time_limit(0);
      $partner1 = $req->input('partner1');
      $partner2 = $req->input('partner2');
      $currency = $req->input('currency');
      $month = $req->input('month');
      $year = $req->input('year');
      $total1 = array();
      $total2= array();
      if ($month != 0) {
        $dt = Carbon::create($year, $month, 1, 0);
        $endyear = Carbon::create(($year), $month + 1, 1, 0);
        while ($dt->diffInDays($endyear) > 2) {
          $count = InvoiceModel::where('usersatid', $partner1)
          ->where('currency', $currency)
          ->whereBetween('created_at', [$dt->toDateString(), $dt->addDays(2)->toDateString()])
          ->count();
          $total1[$dt->toDateTimeString()] = $count;
        }
        $dt = Carbon::create($year, $month, 1, 0);
        while ($dt->diffInDays($endyear) > 2) {
          $count = InvoiceModel::where('usersatid', $partner2)
          ->where('currency', $currency)
          ->whereBetween('created_at', [$dt->toDateString(), $dt->addDays(2)->toDateString()])
          ->count();
          $total2[$dt->toDateTimeString()] = $count;
        }
      }else {
        $dt = Carbon::create($year, 1, 1, 0);
        if (Carbon::now()->diffInYears(Carbon::parse($year)) == 0) {
          $endyear = Carbon::now();
        }else {
          $endyear = Carbon::create(($year+1), 1, 1, 0);
        }
        while ($dt->diffInWeeks($endyear) > 2) {
          $count = InvoiceModel::where('usersatid', $partner1)
          ->where('currency', $currency)
          ->whereBetween('created_at', [$dt->toDateString(), $dt->addWeeks(2)->toDateString()])
          ->count();
          $total1[$dt->toDateTimeString()] = $count;
        }
        $dt = Carbon::create($year, 1, 1, 0);
        while ($dt->diffInWeeks($endyear) > 2) {
          $count = InvoiceModel::where('usersatid', $partner2)
          ->where('currency', $currency)
          ->whereBetween('created_at', [$dt->toDateString(), $dt->addWeeks(2)->toDateString()])
          ->count();
          $total2[$dt->toDateTimeString()] = $count;
        }
      }
      $response = array($total1, $total2);
      return $response;
    }
}
