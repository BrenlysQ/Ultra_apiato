<?php

namespace App\Containers\Payments\UI\WEB\Controllers;

use App\Ship\Parents\Controllers\WebController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Payments\Gateways\CreditCard\CreditCardHandler;
use App\Containers\Payments\UI\Web\Requests\ProcessTDCRequest;
class CreditCard extends WebController
{
  public function AddTransaction(ProcessTDCRequest $req){
    CreditCardHandler::ProcessPayment($req);
  }
}
