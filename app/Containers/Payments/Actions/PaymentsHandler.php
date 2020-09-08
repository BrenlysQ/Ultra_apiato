<?php

namespace App\Containers\Payments\Actions;
use App\Ship\Parents\Actions\Action;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Containers\Payments\Models\PaymentModel;
use App\Containers\Payments\Gateways\BankTransfer\Model\BankTransferModel;
use App\Containers\Payments\Gateways\CreditCard\Model\PagueloFacilModel;
use App\Containers\Payments\Tasks\VerifyGateway;

class PaymentsHandler extends Action{
  public static function NewPayment($data = false){
    $payment = new PaymentModel();
    $payment->idinvoice = $data->idinvoice;
    $payment->amount = $data->amount;
    $payment->sat_userdata = $data->sat_userdata;
    $payment->satellite = $data->satellite;
    $payment->sat_userid = $data->sat_userid;
    $payment->idcurrency = $data->idcurrency;
    $payment->customer = $data->customer;
    $payment->st = 1;
    $payment->payment_date = $data->payment_date;
    $payment->payment_gateway = $data->payment_gateway;
    $payment->processedby = $data->processedby;
    $payment->save();
    return $payment;
  }
  public static function GetById($id){
    return json_decode(PaymentModel::where('id',$id)
                  ->with('satellite')
                  ->with('pgateway')
                  ->with('currency')
                  ->with('customer')
                  ->first()->tojson()
                );
  }

  public static function listPayments(){
    return BankTransferModel::with('payment','bank')->get()->toJson();
  }

  public static function listSatPaymentsBanks($id){
    $transfer = PaymentModel::whereHas('invoice', function ($query) use ($id) {
                $query->where('satelite', $id);
                })->where('payment_gateway',3)
                ->get();

    $transfer->load(['pgatewaydata' => function($q) {
          $q->with('bank');
        }]);
    return $transfer;
  }

  public static function listSatPaymentsCredits($id){
    $transfer = PaymentModel::whereHas('invoice', function ($query) use ($id) {
                $query->where('satelite', $id);
                })->where('payment_gateway',1)
                ->get();
    $transfer->load('pgatewaydata');
    return $transfer;
  }

  public static function listCreditPayments(){
    return PagueloFacilModel::with('payment')->get();
  }

  public static function infoCreditPayments($id){
    return PagueloFacilModel::where('id',$id)->with('payment')->first();
  }

  public static function getPaymentsById($id){
    return PaymentModel::where('idinvoice', $id)->get();
  }

  public static function getPaymentsChartsList($request){
    $dt = Carbon::now();
    $payments = PaymentModel::where([
        ['payment_date', '>', $dt->subMonth()],
        ['idcurrency', '=', $request->input('idcurrency')],
        ['payment_gateway', '=' ,$request->input('payment_gateway')],
        ['st', '=', 2]
        ])
      ->get();
    $gateway = (new VerifyGateway())->run($request->input('payment_gateway'));
    return array(
      'payment_gateway' => $gateway,
      'sumatory' => $payments->sum('amount'),
      'id_currency' => $request->input('idcurrency'),
      'payments' => $payments,
    );
  }
}
