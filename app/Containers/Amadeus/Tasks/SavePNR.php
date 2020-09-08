<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result;
use Amadeus\Client\RequestOptions\PnrAddMultiElementsOptions;

class SaveFare extends Task {
	public function run(){
		/*
			This is the last Step. after CreateFOP you can Save The PNR That you have in Context
		*/
		$pnrReply = $client->pnrAddMultiElements(
		    new PnrAddMultiElementsOptions([
		        'actionCode' => PnrAddMultiElementsOptions::ACTION_END_TRANSACT_RETRIEVE //ET: END AND RETRIEVE
		    ])
		);
	}
}