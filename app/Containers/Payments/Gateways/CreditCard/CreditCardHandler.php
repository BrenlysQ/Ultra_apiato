<?php
namespace App\Containers\Payments\Gateways\CreditCard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Containers\Payments\Actions\PgatewayHandler;
use App\Containers\Payments\Actions\PaymentsHandler;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\Payments\Gateways\CreditCard\Model\PagueloFacilModel;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Payments\Gateways\CreditCard\Model\InstaPagoModel;
use Mail;
use App\Mail\CreditCardPayments;
use App\Containers\UltraApi\Tasks\SatelliteEmailCurlTask;

class CreditCardHandler{
  public static function GetConf(){
    return (object) array(
      "onscreen" => true,
      "url" => "http://wepiato.viajesplusultra.com/payment/view",
      "idhtml" => "creditcard",
      "textbutton" => "Pagar Ahora"
    );
  }
  public static function AddPayment($req){
	//dd($req);
    $invoice = InvoicesHandler::Get(Input::get('invid'));
    if(Input::get('totpay') > $invoice->total_amount){
      return false;
    }
    else{
      $data = (object) array(
        'idinvoice' => $invoice->id,
        'amount' => Input::get('totpay'),
        'sat_userdata' => json_encode($invoice->usersatdata),
        'satellite' => $invoice->satellite->id,
        'sat_userid' => $invoice->usersatid,
        'idcurrency' =>  $invoice->currency_data->id,
        'customer' =>  Auth::id(),
        'st' => 1,
        'payment_date' => date('Y-m-d H:i:s'),
        'payment_gateway' => Input::get('pgateway'),
        'processedby' => 0,
      );
      return PaymentsHandler::NewPayment($data);
    }
  }
  public static function InstaPago($req){
    $data = array(
      'KeyId' => getenv('INSTAPAGO_KEYID'),
      'PublicKeyId' => getenv('INSTAPAGO_PKEYID'),
      'Amount' => $req->totpay,
      'Description' => 'Boleteria Aerea',
      'CardHolder' => $req->name_holder,
      'CardHolderID' => $req->doc_id,
      'CardNumber' => preg_replace('/\s+/', '', $req->card_number),
      'CVC' => $req->cvc_number,
      'ExpirationDate' => preg_replace('/\s+/', '', $req->expiry_date),
      'StatusId' => 1,
      'IP' => $req->ip()

    );
    return Curl::to(getenv('INSTAPAGO_ENVIRONMENT'))
      ->withData($data)
      ->asJsonresponse()
      ->post();
  }
  public static function ProcessPayment($req){
    $payment = CreditCardHandler::AddPayment($req);
    $insta_response = CreditCardHandler::Instapago($req);
    $instapago = new InstaPagoModel();
    if($insta_response->success){
      $payment->st = 2;
      InvoicesHandler::MkPayment($payment);
      $instapago->success = 1;
      $instapago->insta_id = $insta_response->id;
      $instapago->insta_reference = $insta_response->reference;
      $instapago->insta_voucher = $insta_response->voucher;
      $response = View::make('gateways.creditcard.succes')->withInvoice($payment->invoice)->render();
      /*Mail::to($payment->usersatdata->email)->send(new CreditCardPayments(json_decode($payment)));*/
      //Correo su pago ha sido procesado menor
    }else{
      $payment->st = 3;
      $instapago->success = 0;
      //Correo su pago no ha sido procesado menor
      $response = View::make('gateways.creditcard.fail')->withInvoice($payment->invoice)->render();
      /*Mail::to($payment->usersatdata->email)->send(new CreditCardPayments(json_decode($payment)));*/
    }
    $payment->save();
    $instapago->insta_response = json_encode($insta_response);
    $instapago->save();
    $instapago->payment()->save($payment);

    return $response;
  }
  public static function RenderForm(){
    return View::make('gateways.creditcard.form')->render();
  }
  public static function GetTab($gateway, $data = array()){
    $html = View::make('gateways.creditcard.tab')->render();
    return array(
      "content" => $html,
      "name" => "Tarjeta credito/debito",
      "short_name" => "Credito",
      "id" => $gateway->id
    );
  }
  public static function pagueloFacil($req){

    $payment = CreditCardHandler::AddPayment($req);
    if(!$payment){
      return 'El monto que ingresó es mayor al monto a cancelar, por favor, revise su compra e intente nuevamente';
    }else{
      $paguelofacil = new PagueloFacilModel();
      $paguelofacil->codoper = $req->input('CODOPER');
      $paguelofacil->status = $req->input('Status');
      $paguelofacil->amount = $req->input('totpay');
      $paguelofacil->auth_token = $req->input('AUTH_TOKEN');
      $paguelofacil->response = $req->input('Response_Text');
      $paguelofacil->date = $req->input('Date');
      $paguelofacil->time = $req->input('Time');
      $paguelofacil->cardtype = $req->input('CardType');
      $paguelofacil->name = $req->input('Name');
      $paguelofacil->lastname = $req->input('LastName');
      $paguelofacil->email = $req->input('Email');

      $payment->st = 2;
      InvoicesHandler::MkPayment($payment);

      $payment->save();
      $paguelofacil->save();
      $paguelofacil->payment()->save($payment);

      $payment->load(
          'processor',
          'currency')
          ->load(['invoice' => function($q) {
            $q->with(['items' => function($q) {
              $q->with('invoiceable');
            }]);
          }])
		  ->load('pgatewaydata');
      $payment->load(['satellite_token' => function($q){
        $q->with('token');
      }]);
	  $payment->load('customer');
      $response = (new SatelliteEmailCurlTask())->run($payment->satellite_token,$payment,'creditpayment');

      return $payment;
    }
  }
}
?>
