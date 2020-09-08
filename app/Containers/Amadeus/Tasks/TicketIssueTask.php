<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\DocIssuanceIssueTicketOptions;
use Amadeus\Client\RequestOptions\DocIssuanceIssueCombinedOptions;
use Amadeus\Client\RequestOptions\DocIssuance\Option;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;

class TicketIssueTask extends Task {
	public function run(){
		/*
			Issue e-Ticket for one single TST and retrieve PNR (TTP/T1/ET/RT)
		*/
		$itinerary = ItinModel::where('itinerary_id',Input::get('itinerary'))->first();
		$currency = CommonActions::currencyName($itinerary->itinerary->currency);
		$client = CommonActions::buildAmadeusClient($currency,false);
		$pnrResult = (new RetrievePNRTask())->run(
			Input::get('itinerary'),
			$client
		);
		// if(Input::get('log'))
			CommonActions::clientInteraction('PnrRetrieve',$client);
		$opt = new DocIssuanceIssueTicketOptions([
			'options' => [
				new Option([
					'indicator' => Option::INDICATOR_ITINERARY_RECEIPT,
					'subCompoundType' => 'EMPRA'
				])
			],
		]);
		$issueTicketResponse = $client->docIssuanceIssueTicket($opt);
		//if(Input::get('log'))
			CommonActions::clientInteraction('docIssuanceIssueTicket',$client);
		$logoutResponse = $client->securitySignOut();
		if ($issueTicketResponse->status === Result::STATUS_OK) {
			$response = CommonActions::CreateObject();
			$response->modified = true;
			$response->response = $issueTicketResponse->response;
			return $response;
		}else{
			$response = CommonActions::CreateObject();
			$response->modified = false;
			$response->response = $issueTicketResponse->response;
			return $response;
		}
	}
}