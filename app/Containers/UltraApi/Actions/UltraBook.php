<?php
namespace App\Containers\UltraApi\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Containers\Payments\Actions\PaymentsHandler;
use App\Containers\Insurance\Actions\InsuranceHandler;
use App\Mail\ItineraryStored;
use Illuminate\Support\Facades\Input;
use App\Containers\Payments\Actions\PgatewayHandler;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\Invoice\Actions\InvoiceHandler;
use App\Containers\Hotusa\Models\HotusaBooking;
use App\Containers\Freelance\Models\FreelanceModel;
use App\Containers\Hotusa\Actions\PriceHandler;
use App\Containers\Invoice\Models\ItemsModel;
use App\Commons\CommonActions;
use App\Containers\Hotusa\Tasks\CreateHotBookingTask;
use App\Containers\UltraApi\Tasks\CreateItinTask;
//use App\Containers\UltraApi\Tasks\AddItemInvoiceTask;
use App\Containers\UltraApi\Tasks\CreateInvoiceTask;
use App\Containers\Insurance\Tasks\CreateVoucherTask;
use App\Containers\UltraApi\Tasks\CreateItemInvoiceTask;
use App\Containers\UltraApi\Tasks\HandlerFreelanceBook;
use App\Containers\Freelance\Tasks\AddSaleToFreelance;
use Carbon\Carbon;
class UltraBook extends Action{
    public static function Book($data){
		if(Input::get('seller') > 0){
      $data->seller = Input::get('seller');
      (new AddSaleToFreelance())->run($data->seller);
		}else{
			$data->seller = null;
		}
      $intin_invoiceable = (new CreateItinTask())->run($data);
      $invoice = (new CreateInvoiceTask())->run($intin_invoiceable);
      (new CreateItemInvoiceTask())->run($intin_invoiceable,$invoice);
      if ($data->freelance) {
        (new HandlerFreelanceBook())->run($data->freelance);
      }
      $insuranced = Input::get('insuranced',false);
      if($insuranced){
		  //echo 'un ratito'; die;
		  $quotation = json_decode(Input::get('insurance_data','{}'));
		  //print_r($quotation); die;
          $insu_invoiceable = (new CreateVoucherTask())->run(false,$quotation);
          (new CreateItemInvoiceTask())->run($insu_invoiceable,$invoice);
      }
      $ret = CommonActions::CreateObject();
      $ret->invoiced = true; //nueva
      $ret->data = InvoiceHandler::getInfoInvoice($invoice->id);
      return $ret;
    }
	public static function insuranceBook($pax_details,$quotation){
		$insu_invoiceable = (new CreateVoucherTask())->run($pax_details,$quotation);
		if(Input::get('freelance') != null){
			$freelance = FreelanceModel::where('email', Input::get('freelance'))->first();
      $insu_invoiceable->data->seller = $freelance->id;
      (new AddSaleToFreelance())->run($freelance->id);
		}
		$invoice = (new CreateInvoiceTask())->run($insu_invoiceable);
		(new CreateItemInvoiceTask())->run($insu_invoiceable,$invoice);
		$ret = CommonActions::CreateObject();
		$ret->invoiced = true; //nueva
		$ret->data = InvoiceHandler::getInfoInvoice($invoice->id);
		return $ret;
	}
    public static function hotelBooking($prebook,$option,$payload){

      $infoHotBook = (new CreateHotBookingTask())->run($prebook->total_currency);
		//print_r($prebook); die;
      $fares = PriceHandler::PriceBuild($prebook->total_base_amount,$infoHotBook->currencies);

      $booking = new HotusaBooking();
      $booking->booking_id = $prebook->booking_id;
      $booking->total_amount = $fares->GlobalFare->TotalAmount;
      $booking->currency = $infoHotBook->currency;
      $booking->file_number = $prebook->file_number;
      $booking->short_booking_id = 0;
      $booking->pre_book = json_encode($prebook);
      $booking->confirm_book = '';
      $booking->booking_bonus = '';
	  $booking->date_in = Carbon::parse($payload->fechaentrada)->format('Y-m-d');
	  $booking->date_out = Carbon::parse($payload->fechasalida)->format('Y-m-d');
	  $booking->option = json_encode($option);
	  $booking->pax_responsible = json_encode(Input::get('data_pax'));
      $booking->save();
	  
	  if(isset($option->seller)){
      $seller = $option->seller;
      (new AddSaleToFreelance())->run($option->seller);
	  }else{
		  $seller = null;
	  }
      $invoicedata = array(
        'total_amount' => $fares->GlobalFare->TotalAmount,
        'total_tax' => 0,
        'total_paid' => 0,
        'total_base' => $fares->GlobalFare->BaseInter,
        'total_fee' => $fares->GlobalFare->FeeAmount,
        'usersatdata' => $infoHotBook->usersatdata,
        'usersatid' => $infoHotBook->usersatid,
        'satelite' => $infoHotBook->satelite,
        'currency' => $infoHotBook->currency,
        "payment_gateway" => $infoHotBook->pgateway,
		"id_freelance" => $seller,
      );

      $invoice = InvoicesHandler::New($invoicedata);
      $invoice->save();

      $itemsdata = array(
        'fee' => $fares->GlobalFare->FeeAmount,
        'total_amount' => $fares->GlobalFare->TotalAmount,
        'total_base' => $fares->GlobalFare->BaseInter,
        'total_tax' => 0,
        'invoice' => $invoice->id
      );

      $items = InvoicesHandler::NewItems($itemsdata);
      $booking->item()->save($items);

      $ret = CommonActions::CreateObject();
      //En la nueva respuesta a UltraSite debe ir un Objeto con 2 propiedades
      //$ret->invoiced = true
      //$ret->data = InvoiceHandler::getInfoInvoice($invoice->id); Esta es la nueva funcion para obtener la
      //informacion de la factura, en el transcuros del dia seguire adaptando todo a esta nueva funcion
      $ret->invoiced = true; //nueva
      $ret->data = InvoiceHandler::getInfoInvoice($invoice->id);
      return json_encode($ret);
    }
}
