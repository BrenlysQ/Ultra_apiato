<?php

namespace App\Containers\Payments\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Payments\Actions\PaymentsHandler;
use App\Containers\Payments\Models\PaymentModel;
use App\Helpers\Api\Caller\ApiCall;
use Illuminate\Http\Request;

class ControllerPayments extends ApiController
{
  public function GetPaymentsList(){
    return PaymentsHandler::listPayments();
  }

  public function GetPaymentsSatList(Request $request){
    $id = $request->input('id',false);
    return PaymentsHandler::listSatPaymentsBanks($id);
  }

  public function GetCreditPaymentsSatList(Request $request){
    $id = $request->input('id',false);
    return PaymentsHandler::listSatPaymentsCredits($id);
  }

  public function GetPaymentsCreditList(){
    return PaymentsHandler::listCreditPayments();
  }

  public function GetCreditCardInfo(Request $request){
  	$id = $request->input('id',false);
  	return PaymentsHandler::infoCreditPayments($id);
  }

  public function getPaymentsById(Request $request){
  	$id = $request->input('id',false);
  	return PaymentsHandler::getPaymentsById($id);
  }

  public function GetPaymentsChartsList(Request $request){
    return PaymentsHandler::getPaymentsChartsList($request);
  }

}
