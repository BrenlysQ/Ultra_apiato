<?php

namespace App\Containers\Freelance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Input;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Freelance\Models\FreelanceFeaturesModel;
use App\Containers\Invoice\Models\PaymentModel;
use Carbon\Carbon;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;


class GetbalanceTask extends Task
{
    public function run($req)
    {
      $non_paid = 0;
      $blocked = 0;
      $satellite = $req->input('satellite');
      $freelance = FreelanceModel::where('email', $satellite)->first();
      $invoices = InvoiceModel::where('satelite', $freelance->id_satellite)->get();
      foreach ($invoices as $invoice) {
        if ($invoice->administration_status == 1 && ($invoice->total_paid >= $invoice->total_base)){
          $non_paid += $invoice->total_fee;
        }elseif ($invoice->administration_status == 1 && ($invoice->total_paid < $invoice->total_base)){
          $blocked += $invoice->total_fee;
        }
      }
      $last = PaymentModel::where('id_freelance', $freelance->id)->lastest()->first();
      $balance = (object) array(
        'non_paid' => $non_paid ,
        'blocked' => $blocked,
        'last_payment' => $last->created_at,
      );
      return $balance;
    }
}
