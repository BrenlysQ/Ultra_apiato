<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Commons\CommonActions;
use Amadeus\Client\RequestOptions\PnrRetrieveOptions;
use Illuminate\Support\Facades\Input;

class RetrievePNRTask extends Task {
	public function run($data = false,$client = false){
		if(!$client)
			$client = (new AmadeusAuthTask())->run();
		if(Input::get('debug')){
			$locator = Input::get('locator');
		}else{ 
			$locator = $data;
		}
		$pnrContent = $client->pnrRetrieve(
			new PnrRetrieveOptions(['recordLocator' => $locator])
		);
		return $pnrContent;
	}
} 