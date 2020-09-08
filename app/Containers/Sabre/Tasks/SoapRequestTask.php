<?php

namespace App\Containers\Sabre\Tasks;

use SoapClient;
use App\Ship\Parents\Tasks\Task;
use App\Commons\CommonActions;
use App\Containers\Sabre\Actions\SabreAuthSoap;
use App\Containers\Sabre\Actions\SACSSoapClient;

/**
 * Class AuthSoapTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class SoapRequestTask extends Task
{
    public function run($request, $sabre_api)
    {
		//print_r($sabre_api); die;
        $token = SabreAuthSoap::GetSoapToken();
        $token = $token['access_token'];
        $soapclient = new SACSSoapClient($token);
        //print_r($soapclient); die;
        $soapclient->setLastInFlow(false);
        $response_xml = $soapclient->doCall($request,$sabre_api);
		//print_r($response_xml); die;
        $response_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response_xml);
        //print_r($response_xml); die;
        $response = json_decode(CommonActions::XML2JSON($response_xml));

        return json_encode($response);
    }
}
?>
