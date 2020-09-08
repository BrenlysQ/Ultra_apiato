<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client\Result; 
use Amadeus\Client\RequestOptions\FopCreateFopOptions;
use Amadeus\Client\RequestOptions\Fop\Group;
use Amadeus\Client\RequestOptions\Fop\MopInfo;
use App\Commons\CommonActions;

class CreateFormOfPayment extends Task {
	public function run($client){
		$options = new FopCreateFopOptions([
			'fopGroup' => [
				new Group([
					'mopInfo' => [
						new MopInfo([
							'sequenceNr' => 1,
							'fopCode' => 'CASH'
						])
					]
				])
			]
		]);
		$fopResponse = $client->fopCreateFormOfPayment($options);
		CommonActions::clientInteraction('FOP_CreateFormOfPayment',$client);
		if ($fopResponse->status === Result::STATUS_OK) {
			return $fopResponse;
		}else{
			$response = CommonActions::CreateObject();
			$response->status = 'Ha Ocurrido Un Problema (fopCreateFormOfPayment)';
			$response->messages = 'Error en la forma de pago';
			$response->result = $fopResponse->response;
			return $response;
		}
	}
} 