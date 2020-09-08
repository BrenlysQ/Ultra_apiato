<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Log;
class SACSClient extends Action{
    public static function doCall($headersXml, $body, $action) {
        $soapUrl = getenv('SABRE_ENVIRONMENTSOAP');
        // xml post structure
        $xml_post_string = '<SOAP-ENV:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">'
                . '<SOAP-ENV:Header>'
                . $headersXml
                . '</SOAP-ENV:Header>'
                . '<SOAP-ENV:Body>'
                . $body
                . '</SOAP-ENV:Body>'
                . '</SOAP-ENV:Envelope>';
        $headers = array(
            "Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: " . $action,
            "Content-length: " . strlen($xml_post_string)
        );
        $message = '
        Request to: ' . $action . '


        ' . $xml_post_string;
        Log::debug($message);
        // PHP cURL  for https connection with auth
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_URL, $soapUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
//            curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string); // the SOAP request
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_VERBOSE, false);

        // converting
        $response = curl_exec($ch);
        curl_close($ch);

        $message = '
        Response from: ' . $action . '


        ' . $response;
        Log::debug($message);
        return $response;
    }

}

?>
