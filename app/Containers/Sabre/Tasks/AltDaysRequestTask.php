<?php

namespace App\Containers\Sabre\Tasks;

use App\Containers\Sabre\Actions\SabreAuthSoap;
use App\Containers\Sabre\Actions\SACSSoapClient;
use App\Ship\Parents\Tasks\Task;
use App\Commons\CommonActions;

/**
 * Class PaxPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AltDaysRequestTask extends Task
{
    public function run($payload)
    {
    	$token = SabreAuthSoap::GetSoapToken();
	    $token = $token['access_token'];
	    $soapclient = new SACSSoapClient($token);
	    $soapclient->setLastInFlow(false);
	    $response_xml = $soapclient->doCall($payload,'BargainFinderMax_ADRQ');
	    $response_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response_xml);
	    $response = json_decode(CommonActions::XML2JSON($response_xml));
	    return $response;
    }
}