<?php

namespace App\Containers\Payments\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Payments\UI\WEB\Requests\ViewPaymentRequest;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Containers\Payments\Actions\PgatewayHandler;
use App\Containers\UltraApi\Actions\Banks\BanksHandler;
class PaymentController extends WebController
{

    /**
     * @return  \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
  public function pagteway(Request $req){
    $invid = $req->get('invid');
    $invoice = InvoicesHandler::Get($invid);
    $form = PgatewayHandler::GetForm($req->get('pid'), $invoice);
    return $form;
  }
  public function payingview(ViewPaymentRequest $req){   // user say welcome
    //CHECK IF SAT DOMAN NAME IS CORRECT
    //return $req->server('HTTP_REFERER');
    //dd("llslldl");
    $invoice = InvoicesHandler::VerifyPermission($req);
    if(!$invoice){
      abort(404);
    }
    //dd($invoice);
    //dd($invoice->invoiceable);
    $gateways = CurrenciesHandler::GetPgateWays($invoice->invoiceable->itinerary);
    $gateways = PgatewayHandler::GetTabs($gateways, (object) array('currency' => $invoice->currency_data->id));
    $banklist = BanksHandler::GetbyCurrency($invoice->currency_data->id);
    //dd('$invoice');
    if($invoice->st < 1){
      return view('payments.paying')
            ->withPgateways($gateways)
            ->withBanklist(json_decode($banklist))
            ->withInvoice($invoice);
    }else{
      //Cargos los pagos asociados a la factura asi como los datos estras de cada pago
      $invoice->load('payments');
      $invoice->payments->load('pgateway')->load('currency');
      return view('payments.paid')
            ->withPayments($invoice->payments)
            ->withInvoice($invoice);
    }
    // return view('gateways.banktransfer.transadded');
  }
}
