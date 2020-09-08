<?php

namespace App\Containers\Invoice\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\Invoice\Actions\InvoiceHandler;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Containers\User\Models\User;
use Illuminate\Http\Request;
use App\Helpers\Api\Caller\ApiCall;



class ControllerInvoices extends ApiController
{
    public function GetInvoiceList(){
      return InvoiceHandler::listInvoices();
    }
    public function InvoiceInfo(Request $request){
	  	$id = $request->input('id',false);
	  	return InvoiceHandler::getInfoInvoice($id);
	}
	public function InvoiceListSat(Request $request){
	  	$id = $request->input('id',false);
	  	return InvoiceHandler::satListInvoice($id);
	}
	public function InvoiceIssue(Request $request){
	  	$id = $request->input('id',false);
	  	return InvoiceHandler::issueInvoice($id);
	}
  public function changeInvoiceStatus(Request $request){
	  	return InvoiceHandler::changeInvoiceStatus($request);
	}
}
