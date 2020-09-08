<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use App\Mail\EmitedInsuranceMail;
use App\Containers\Insurance\Actions\InsuranceHandler;


class EmitItemsTask extends Task
{

  public function run($invoice){
    foreach ($invoice->items as $item) {
      if ($item->invoice_type == 'VoucherModel' ) {
        $voucher = InsuranceHandler::emiteCot($invoice);
        Mail::to($invoice->contact_pax->email)->send(new RegisteredFreelanceMessage($data));
      }
      elseif ($item->invoice_type == 'HotusaBooking') {
        $hotel = null;
      }
      elseif ($item->invoice_type == 'ItinModel') {
        $itinerary = null;
      }
      return $response = (object) array(
        'insurance' => $voucher,
        'hotel' => $hotel,
        'itinerary' => $itinerary
      );
    }
  }
}
