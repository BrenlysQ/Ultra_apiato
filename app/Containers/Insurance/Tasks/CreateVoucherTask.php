<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\Invoice\Actions\InvoiceHandler;
use App\Containers\Invoice\Models\ItemsModel;
use App\Containers\Insurance\Models\VoucherModel;
use App\Containers\Freelance\Models\FreelanceModel;
use Carbon\Carbon;

/**
 * Class UpdateUserTask.
 *
 * @author Plusultra, C.A.
 */
class CreateVoucherTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($pax_details,$quotation, $freelance = null)
    {
      $child = 0;
      $adult = 0;
      $oldman = 0;
      $plan_id = $quotation->id_plan;
      $insu_data = json_decode(Input::get('insurance_data'));
      $currency = $quotation->currency;
	  $payload = Input::get('payload');
      $id_cot = $quotation->id_cot;
      $price = (object) $quotation->price;
      $global = (object) $price->GlobalFare;
      $total_price = $global->TotalAmount;
      $total_fee = $global->FeeAmount;
      $total_base = $global->BaseAmount;  
	$quotation->payload = (object)$payload;
      if($pax_details){
        foreach($pax_details as $pax){
          $pax = (object) $pax;
          if($pax->type == 'chd'){
            $child++;
          }elseif($pax->type == 'adt'){
            $adult++;
          }else{
            $oldman++;
          }
        }
      }else{
		  
        $child = $payload->c;
        $adult = $payload->a;
        $oldman = $payload->v;
      }

	    $response = CommonActions::CreateObject();
      $response->adults = $adult;
      $response->childs = $child;
      $response->oldmans = $oldman;
      $response->paxes = $pax_details;
      if($payload != null){ 
        $plan = CommonActions::CreateObject();
        $plan->plan = $quotation->plan_name;
        $plan->payload = (object) $payload;
        $response->plan = $plan;
      }else{
        $response->plan = $quotation->plan_name;
      }
	
      $usersatdata = Input::get('usersat');
      $usersatid = (json_decode($usersatdata))->id;
      $response = json_encode($response);

      $voucher = new VoucherModel();
      $voucher->response = $response;
      $voucher->quotation_id = $id_cot;
      $voucher->voucher_id = 0;
      $voucher->document_url = '';
      $voucher->voucher_status = '';
      $voucher->currency = $currency;
      $voucher->plan = $plan_id;
      $voucher->destination_city = $quotation->payload->d;
      $voucher->departure_city = $quotation->payload->o;
      $voucher->save();
     $satellite = Input::get('freelance_id');
      $contactpax = json_decode(json_encode($pax_details), true);
      //AL CREAR UN INVOI$CEABLE LA RESPUESTA DEL TASK DEBE SER
      //UN OBJECT CON 2 PROPIEDADES obj y data obj=Invoiceable data = data del invoiceable
      $ret = CommonActions::CreateObject();
      $ret->obj = $voucher; //INVOICEABLE
      $ret->data = (object) array(
        'fee' => 0,
        'total_amount' => $total_price,
        'total_amount' => $total_price,
        'contactpax' => json_encode($contactpax[0]),
        'total_base' => $total_base,
        'total_tax' => 0,
		'feepu' => $total_fee,
		'usersatid' => $usersatid,
		'usersatdata' => $usersatdata,
		'currency' => $currency,
		'satelite' => $satellite
      );
		  return $ret;
      //$items = InvoicesHandler::NewItems($ret->data);
      //$voucher->item()->save($items);

      //$ret = CommonActions::CreateObject();
      //En la nueva respuesta a UltraSite debe ir un Objeto con 2 propiedades
      //$ret->invoiced = true
      //$ret->data = InvoiceHandler::getInfoInvoice($invoice->id); Esta es la nueva funcion para obtener la
      //informacion de la factura, en el transcuros del dia seguire adaptando todo a esta nueva funcion
      /*$ret->invoiced = true; //nueva
      $ret->data = InvoiceHandler::getInfoInvoice($invoice->id);*/
      //return true;

    }

}
