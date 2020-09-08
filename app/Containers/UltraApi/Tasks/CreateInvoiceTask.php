<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use Illuminate\Support\Facades\Auth;
/**
 * Class CreateInvoiceTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class CreateInvoiceTask extends Task
{

    public function run($invoiceable)
    {
		if(isset($invoiceable->data->seller)){
			$seller = $invoiceable->data->seller;
		}else{
			$seller = null;
		}
      $invoicedata = array(
        'total_amount' => 0,
        'total_tax' => 0,
        'total_paid' => 0,
        'total_base' => 0,
        'total_fee' => 0,
        'usersatdata' => $invoiceable->data->usersatdata,
        'usersatid' => $invoiceable->data->usersatid,
        'contact_pax' => trim(Input::get('contactpax'),'[]'),
        'satelite' => $invoiceable->data->satelite,
        'currency' => $invoiceable->data->currency,
        "payment_gateway" => Input::get('pgateway',1),
		'id_freelance' => $seller
      );
      //$data->itineraryid = $itinerary->id;
      $invoice = new InvoiceModel($invoicedata);
      $invoice->save();
      return $invoice;
    }
}
