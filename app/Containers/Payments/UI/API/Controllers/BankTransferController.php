<?php

namespace App\Containers\Payments\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Payments\Gateways\BankTransfer\BankTransferHandler;
use Illuminate\Http\Request;
class BankTransferController extends ApiController
{
  public function AddTransfer(Request $req){
    return BankTransferHandler::addTransfers($req);
  }

  public function BtUpdate(Request $req){
    return BankTransferHandler::Update($req);
  }
}
