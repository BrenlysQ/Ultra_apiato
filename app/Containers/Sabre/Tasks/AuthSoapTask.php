<?php

namespace App\Containers\Sabre\Tasks;

use SoapClient;
use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
use App\Containers\Sabre\Commons\SabreCommons;
/**
 * Class AuthSoapTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class AuthSoapTask extends Task
{
    public function run($ipcc = false)
    {
      if(!$ipcc){
        $ipcc = getenv('SABRE_GROUP');
      }
      $date = date("Y-m-d\TH:i:s");
      $xmlstr = '<?xml version="1.0" encoding="UTF-8"?>
            <SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/" xmlns:eb="http://www.ebxml.org/namespaces/messageHeader" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:xsd="http://www.w3.org/1999/XMLSchema">
                <SOAP-ENV:Header>
                    <eb:MessageHeader SOAP-ENV:mustUnderstand="1" eb:version="1.0">
                        <eb:From>
                            <eb:PartyId type="urn:x12.org:IO5:01">999999</eb:PartyId>
                        </eb:From>
                        <eb:To>
                            <eb:PartyId type="urn:x12.org:IO5:01">123123</eb:PartyId>
                        </eb:To>
                        <eb:CPAId>' . $ipcc . '</eb:CPAId>
                        <eb:ConversationId>Id-1</eb:ConversationId>
                        <eb:Service eb:type="OTA">SessionCreateRQ</eb:Service>
                        <eb:Action>SessionCreateRQ</eb:Action>
                        <eb:MessageData>
                            <eb:MessageId>1000</eb:MessageId>
                            <eb:Timestamp>' . $date . '</eb:Timestamp>
                            <eb:TimeToLive>2016-09-25T11:15:12Z</eb:TimeToLive>
                        </eb:MessageData>
                    </eb:MessageHeader>
                    <wsse:Security xmlns:wsse="http://schemas.xmlsoap.org/ws/2002/12/secext" xmlns:wsu="http://schemas.xmlsoap.org/ws/2002/12/utility">
                        <wsse:UsernameToken>
                            <wsse:Username>' . getenv('SABRE_USERID') . '</wsse:Username>
                            <wsse:Password>' . getenv('SABRE_SECRET') . '</wsse:Password>
                            <Organization>' . $ipcc . '</Organization>
                            <Domain>DEFAULT</Domain>
                        </wsse:UsernameToken>
                    </wsse:Security>
                </SOAP-ENV:Header>
                <SOAP-ENV:Body>
                    <eb:Manifest SOAP-ENV:mustUnderstand="1" eb:version="1.0">
                        <eb:Reference xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="cid:rootelement" xlink:type="simple"/>
                    </eb:Manifest>
                </SOAP-ENV:Body>
            </SOAP-ENV:Envelope>';

        $client = new SoapClient(null, array(
            'location' => getenv('SABRE_ENVIRONMENTSOAP'),
            'uri' => getenv('SABRE_ENVIRONMENTSOAP'),
            'trace' => 1,
            'exceptions' => true
        ));
        //echo $xmlstr; die;
        $response = $client->__doRequest($xmlstr,getenv('SABRE_ENVIRONMENTSOAP'),getenv('SABRE_ENVIRONMENTSOAP'),'1');
        //echo $response; die;
        $xml = simplexml_load_string($response);
        $ns = $xml->getNamespaces(true);
        $token['access_token'] = (string) $xml->children($ns['soap-env'])->Header->children($ns['wsse'])->Security->children($ns['wsse']);
        //echo $response; die;
        $token['expires_in'] = 900;
        return $token['access_token'];
    }
}
?>
