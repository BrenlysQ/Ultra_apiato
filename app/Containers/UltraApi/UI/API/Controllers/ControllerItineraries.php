<?php

namespace App\Containers\UltraApi\UI\API\Controllers;
use App\Ship\Parents\Controllers\ApiController;
use App\Containers\UltraApi\Actions\Itineraries\ItinHandler;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use App\Helpers\Api\Caller\ApiCall;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;



class ControllerItineraries extends ApiController
{

	public function UserItineraries(Request $request){
	  	$id = $request->input('id',false);
	  	return ItinHandler::getUserItineraries($id);
	}
	public function test(Request $request){
		$id = $request->input('id',false);
	  return InvoicesHandler::MkPayment($id);
	}
}
