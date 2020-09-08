<?php

namespace App\Containers\Payments\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
class VerifyGateway extends Task
{
    public function run($payment_gateway)
    {
      if ($payment_gateway == 1) {
        $gateway = 'Efectivo';
      }elseif ($payment_gateway == 2) {
        $gateway = 'Tarj. Credito';
      }else{
        $gateway = 'Transferencias';
      }
      return $gateway;
    }
}
