<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use SoapClient;
class SACSSoapClient extends Action{

    private $lastInFlow = false;
    private $actionName;
    private $token;
    public function __construct($token) {
        $this->token = $token;
    }

    public function doCall($request,$action) {
      $this->actionName = $action;
        /*if ($sharedContext->getResult("SECURITY") == null) {
            error_log("SessionCreate");
            $securityCall = new SessionCreateRequest();
            $sharedContext->addResult("SECURITY", $securityCall->executeRequest());
        }*/
        //$sacsClient = new SACSClient();
        $result = SACSClient::doCall(SACSSoapClient::getMessageHeaderXml($this->actionName) . $this->createSecurityHeader($this->token), $request, $this->actionName);
        /*if ($this->lastInFlow) {
            error_log("Ignore and close");
            $this->ignoreAndCloseSession($sharedContext->getResult("SECURITY"));
            $sharedContext->addResult("SECURITY", null);
        }*/
        return $result;
    }

    private function ignoreAndCloseSession($security) {
        $it = new IgnoreTransactionRequest();
        $it->executeRequest($security);
        $sc = new SessionCloseRequest();
        $sc->executeRequest($security);
    }

    private function createSecurityHeader($token) {
        $security = array("Security" => array(
                "_namespace" => "http://schemas.xmlsoap.org/ws/2002/12/secext",
                "BinarySecurityToken" => array(
                    "_attributes" => array("EncodingType" => "Base64Binary", "valueType" => "String"),
                    "_value" => $token
                )
            )
        );
        return XMLSerializer::generateValidXmlFromArray($security);
    }

    public static function createMessageHeader($actionString) {
        $messageHeaderXml = SACSSoapClient::getMessageHeaderXml($actionString);
        $soapVar = new SoapVar($messageHeaderXml, XSD_ANYXML, null, null, null);
        return new SoapHeader("http://www.ebxml.org/namespaces/messageHeader", "MessageHeader", $soapVar, 1);
    }

    private static function getMessageHeaderXml($actionString) {
        //print_r($actionString); die;
        $messageHeader = array("MessageHeader" => array(
                "_namespace" => "http://www.ebxml.org/namespaces/messageHeader",
                "From" => array("PartyId" => "sample.url.of.sabre.client.com"),
                "To" => array("PartyId" => "webservices.sabre.com"),
                "CPAId" => "7TZA",
                "ConversationId" => "convId",
                "Service" => $actionString,
                "Action" => $actionString,
                "MessageData" => array(
                    "MessageId" => "1000",
                    "Timestamp" => "2001-02-15T11:15:12Z",
                    "TimeToLive" => "2001-02-15T11:15:12Z"
                )
            )
        );
        return XMLSerializer::generateValidXmlFromArray($messageHeader);
    }

    public function setLastInFlow($lastInFlow) {
        $this->lastInFlow = $lastInFlow;
    }

}



?>