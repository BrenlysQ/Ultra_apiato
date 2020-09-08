<?php

namespace App\Containers\Payments\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Containers\Payments\Gateways\CreditCard\CreditCardHandler;
class CreditCardController extends ApiController
{
  public function AddCreditCardTransfer(Request $req){
    return CreditCardHandler::pagueloFacil($req);
  }

}
