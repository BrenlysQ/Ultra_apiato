<?php
namespace App\Containers\Payments\Gateways\BankTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Containers\UltraApi\Actions\Banks\BanksHandler;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\Payments\Actions\PgatewayHandler;
use App\Containers\Payments\Actions\PaymentsHandler;
use Mail;
use App\Commons\CommonActions;
use App\Mail\BankTransferEmail;
use App\Containers\Payments\Gateways\BankTransfer\Model\BankTransferModel;
use Carbon\Carbon;
use App\Containers\Satellite\Models\API_satellite;
use App\Containers\UltraApi\Tasks\SatelliteEmailCurlTask;

class BankTransferHandler{
  public static function GetConf(){
    return (object) array(
      "onscreen" => false,
      "url" => "http://ssssss.app?id="
    );
  }
  private static function confirm($transfer){
    $payment = $transfer->payment;
    //envio el pago al manejaor de las facturas para confirmarlo
    InvoicesHandler::MkPayment($payment);
    $payment->st = 2;
    $payment->save();
    //SI el stus del invoice es 0 despues e hacer el apgo quiere ecir que fue un pago parcial
    //Po lo que deo enviarle la notificacion al cliente de que su pago PARCIAL fue aprobado
    //en caso contrario no envio naa ya que al cliente se le envia antes un correo indicando que la oren completa fue aprobada
    if($payment->invoice->st == 0){
      $payment->load(
          'processor',
          'currency')
          ->load(['invoice' => function($q) {
            $q->with(['items' => function($q) {
              $q->with('invoiceable');
            }]);
          }])
          ->load(['pgatewaydata' => function($q) {
            $q->with('bank');
          }]);
      $payment->load(['satellite_token' => function($q){
        $q->with('token');
      }]);
  }
    $response = (new SatelliteEmailCurlTask())->run($payment->satellite_token,$payment,'bankstransfer');
    return $response;
  }
  private static function bounce($transfer){
    $payment = $transfer->payment;
    $payment->st = 3;
    $payment->save();
    if($payment->invoice->st == 0){
      $payment->load(
          'processor',
          'currency')
          ->load(['invoice' => function($q) {
            $q->with(['items' => function($q) {
              $q->with('invoiceable');
            }]);
          }])
          ->load(['pgatewaydata' => function($q) {
            $q->with('bank');
          }]);
      $payment->load(['satellite_token' => function($q){
        $q->with('token');
      }]);
  }

    $response = (new SatelliteEmailCurlTask())->run($payment->satellite_token,$payment,'bankstransfer');
    return $response;
  }

  public static function Update($req){
    $st = $req->get('st');
    $transfer = BankTransferModel::findOrfail($req->get('idtransfer'));
    $transfer->load(['payment' => function($q){
      $q->with('invoice','customer');
    }]);
    if($st == 1){  
      return BankTransferHandler::confirm($transfer);
    }elseif ($st == 3) {
      return BankTransferHandler::bounce($transfer); 
    }

  }
  private static function AddPayment($req){
    $invoice = InvoicesHandler::Get($req->input('invoiceid'));
    $data = (object) array(
      'idinvoice' => $invoice->id,
      'amount' => $req->input('totpay'),
      'sat_userdata' => json_encode($invoice->usersatdata),
      'satellite' => $invoice->satellite->id,
      'sat_userid' => $invoice->usersatid,
      'idcurrency' =>  $invoice->currency_data->id,
      'customer' =>  Auth::id(),
      'st' => 1,
      'payment_date' => $req->input('payment_date'),
      'payment_gateway' => $req->input('pgateway'),
      'processedby' => 0,
    );
    return PaymentsHandler::NewPayment($data);
  }
  public static function addTransfers($req){
    //Get invocie stored in BD $invoice
    $payment = BankTransferHandler::AddPayment($req);
    $transfer = new BankTransferModel();
    $transfer->idbank = $req->input('bankid');
    $transfer->confirmation = $req->input('confirmation_number');
    $transfer->save();
    $transfer->payment()->save($payment);
    $payment = json_decode($payment->load(
      'satellite',
      'currency',
      'customer')
      ->load(['invoice' => function($q) {
        $q->with(['items' => function($q) {
          $q->with('invoiceable');
        }]);
      }])
      ->load(['pgatewaydata' => function($q) {
        $q->with('bank');
      }])
      ->toJson());
    //Solucionar el peo con el email
    $noti_payment = CommonActions::CreateObject();
    $noti_payment->payment = true;
    $noti_payment->invoice = $payment;
    return json_encode($noti_payment);
    //Mail::to(Auth::user()->email)->send(new BankTransferEmail($payment));
    //return 'ssss';
    //return view('gateways.banktransfer.transadded')->withInvoice($payment->invoice);
  }
  public static function RenderForm($gateway, $data){
    return View::make('gateways.banktransfer.form',[
      'banklist' => json_decode(BanksHandler::GetbyCurrency($data->currency_data->id)),
      'gateway' => $gateway,
      'invoice' => $data
    ])->render();
  }

  public static function GetTab($gateway, $data = array()){
    $html = View::make('gateways.banktransfer.tab',[
      'banklist' => json_decode(BanksHandler::GetbyCurrency($data->currency))
    ])->render();
    return array(
      "content" => $html,
      "name" => "Transferencia",
      "short_name" => "Transferencia",
      "id" => $gateway->id
    );
  }
}
?>
