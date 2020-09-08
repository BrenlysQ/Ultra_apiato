<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Containers\Invoice\Models\ItemsModel;
/**
 * Class CreateItemInvoiceTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class CreateItemInvoiceTask extends Task
{

    public function run($invoiceable,$invoice)
    {
    $item_data = array( // DATA REQUERIDA DEL INVOICEABLE
        'fee' => $invoiceable->data->fee,
	      'feepu' => $invoiceable->data->feepu,
        'total_amount' => $invoiceable->data->total_amount,
        'total_base' => $invoiceable->data->total_base,
        'total_tax' => $invoiceable->data->total_tax,
        'invoice' => $invoice->id
      );
      $invoice->total_amount = $invoice->total_amount + $invoiceable->data->total_amount;
      $invoice->total_tax = $invoice->total_tax + $invoiceable->data->total_tax;
      $invoice->total_base = $invoice->total_base + $invoiceable->data->total_base;
      $invoice->total_fee = $invoice->total_fee + $invoiceable->data->fee;
      $invoice->feepu = $invoiceable->data->feepu;
      $item = new ItemsModel($item_data);
      $invoiceable->obj->item()->save($item);
      $invoice->save();
    }
}
