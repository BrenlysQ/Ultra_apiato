<?php
namespace App\Containers\Amadeus\Tasks;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Amadeus\Client;
use Amadeus\Client\Params;
use Illuminate\Support\Facades\Log;

class AmadeusAuthTask extends Task { 
	public function run($stateless,$officeId = 'PTY1S2389'){
		/*
			Credenciales de Prueba amadeus
			UserID: WSPUUWPU
			Password: QU1BREVVUw==
			OfficeID: PTY1S2389 o MYCV12119
			
			Credenciales de Produccion amadeus
			UserID: WSPUUWPU
			Password: base64_encode('HJjubv7LczQm')
			OfficeID: MYCV12119
			OrganizationID: LATAM
		*/
		if($stateless){
			$stateful = false;
		}else{
			$stateful = true;
		}
		$params = new Params([
	        'authParams' => [
	            'officeId' => $officeId, //The Amadeus Office Id you want to sign in to - must be open on your WSAP.
	            'userId' => 'WSPUUWPU', //Also known as 'Originator' for Soap Header 1 & 2 WSDL's
	            'passwordData' => 'QU1BREVVUw==' // **base 64 encoded** password
	        ],
	        'sessionHandlerParams' => [
	            'soapHeaderVersion' => Client::HEADER_V4, //This is the default value, can be omitted.
	            'wsdl' => '/home/viajesplus/apiato.viajesplusultra.com/storage/amadeus/1ASIWWPUPUU_PDT_20180216_194156.wsdl', //Points to the location of the WSDL file for your WSAP. Make sure the associated XSD's are also available.
	            'stateless' => $stateless, //Enable stateless messages by default - can be changed at will to switch between stateless & stateful.
				'stateful' => $stateful, //Enable stateful messages by default - can be changed at will to switch between stateless & stateful.
	            'logger' => new Log()
	        ],
	        'requestCreatorParams' => [
	            'receivedFrom' => 'Plusultra Project' // The "Received From" string that will be visible in PNR History
	        ]
	    ]); 
	    return new Client($params);
	}
}