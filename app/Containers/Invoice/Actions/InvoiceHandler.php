<?php
namespace App\Containers\Invoice\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\Hotusa\Models\HotusaBooking;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\User\Models\User;
use Mail;
use App\Mail\ItineraryIssue;
use App\Containers\Insurance\Models\VoucherModel;
use App\Containers\Satellite\Models\API_satellite;
use App\Containers\Invoice\Models\PaymentModel;
use App\Containers\UltraApi\Tasks\SatelliteEmailCurlTask;
use Carbon\Carbon;

class InvoiceHandler extends Action {

  public static function listInvoices(){
    $invoices = InvoiceModel::all();
    $invoices->load(['items' => function($q) {
                        $q->with('invoiceable');
                    }]);
    $invoices->load('satellite');
    return $invoices;

  }
  /* FUNCION UNICA PARA OBTENER LA INFORMACION DE LA FACTURA */

  public static function getInfoInvoice($id){
    $invoice = InvoiceModel::findOrFail($id);
    $invoice->load(['currency_data' => function($q) {
      $q->with('banksinfo');
    }])->load(['satellite' => function($q) {
          $q->with('owner');
    }])->load(['items' => function($q) {
          $q->with('invoiceable');
    }])->load('satellite_main');
    //dd($invoice->items[0]->invoice_type);
    return $invoice;
  }

  /* FIN FUNCION UNICA PARA OBTENER LA INFORMACION DE LA FACTURA */


  public static function infoInvoice($id,$hotel = false){

    if ($hotel) {
      $hotusa_booking = HotusaBooking::where('id',$id)
        ->with('invoice')
        ->first();
      $hotusa_booking->invoice->load(['currency_data' => function($q) {
        $q->with('banksinfo');
      }]);
      $hotusa_booking->invoice->load(['satellite' => function($q) {
            $q->with('owner');
      }]);
      return $hotusa_booking;
    }else{
      $itinerary = ItinModel::where('id',$id)
        ->with('invoice','satellite_main')
        ->first();
      $itinerary->invoice->load(['currency_data' => function($q) {
        $q->with('banksinfo');
      }]);
      $itinerary->load(['satellite' => function($q) {
            $q->with('owner');
      }]);
      return $itinerary;
    }
  }

    public static function infoInvoiceInsurance($id){

        $quot = VoucherModel::where('id',$id)
          ->with('items')
          ->first();
        // $quot->invoice->load(['currency_data' => function($q) {
        //   $q->with('banksinfo');
        // }]);
        // $quot->invoice->load(['satellite' => function($q) {
        //       $q->with('owner');
        // }]);
        return $quot;


  }

  public static function infoHotelInvoice($id){

    $hotusa_booking = HotusaBooking::where('id',$id)
      ->with('invoice')
      ->first();
    $hotusa_booking->invoice->load(['currency_data' => function($q) {
      $q->with('banksinfo');
    }]);
    $hotusa_booking->invoice->load(['satellite' => function($q) {
          $q->with('owner');
    }]);

    return $hotusa_booking;
  }


  public static function satListInvoice($id){
    return ItinModel::where('satelite',$id)->with('item')->get();
  }

  public static function issueInvoice($id){
    /* Buscando Invoice*/
    $invoice = InvoiceModel::findOrFail($id);
    $invoice->st = 3;
    $invoice->save();
    $invoice->load(['items' => function($q) {
            $q->with('invoiceable');
      }]);
    $response = (new SatelliteEmailCurlTask())->run($satellite[0],$invoice,'invoice_issue');
    return $response;
  }

  public static function changeInvoiceStatus($request){
    $request = array(1 => 623,2 => 624,3 => 625,4 => 626);
    $freelance = 39;
    foreach ($request as $id) {
      $invoice = InvoiceModel::where('id',$id)->first();
      $invoice->administration_status = 2;
      $invoice->update();
    }
    $payment = new PaymentModel();
    $payment->invoices = json_encode($request);
    $payment->id_freelance = $freelance;
    $payment->reference = $reference;
    $payment->description = $description;
    $payment->save();
    return $payment;
  }

  public static function getPaidInvoices(){

  }
}
