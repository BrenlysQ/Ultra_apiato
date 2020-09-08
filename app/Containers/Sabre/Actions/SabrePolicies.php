<?php

namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Containers\Sabre\Actions\SabreAuthSoap;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;

class SabrePolicies extends Action{
	
	private static $token;
	public static function getSabrePolicies(){
		
		$data = (object) array (
			"departure_date" => Input::get('DepartureDateTime'),
			"destination_code" => Input::get('DestinationCode'),
			"airline" => Input::get('Code'),
			"location_code" => Input::get('OriginLocation'),
			"class" => Input::get('FareBasis')
		);

		$air_rules='<OTA_AirRulesRQ ReturnHostCommand="true" Version="2.2.1" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
					    <OriginDestinationInformation>
					        <FlightSegment DepartureDateTime="' . $data->departure_date . '">
					            <DestinationLocation LocationCode="' . $data->destination_code . '"/>
					            <MarketingCarrier Code="' . $data->airline . '"/>
					            <OriginLocation LocationCode="' . $data->location_code . '"/>
					        </FlightSegment>
					    </OriginDestinationInformation>
					    <RuleReqInfo>
					        <FareBasis Code="' . $data->class . '"/>
					    </RuleReqInfo>
					</OTA_AirRulesRQ>';

		$token = SabreAuthSoap::GetSoapToken();
	    $token = $token['access_token'];
	    $soapclient = new SACSSoapClient($token);
	    $soapclient->setLastInFlow(false);
	    $response_xml = $soapclient->doCall($air_rules,'OTA_AirRulesLLSRQ');
	    $response_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $response_xml);
	    $response = json_decode(CommonActions::XML2JSON($response_xml));
	    $json_response = CommonActions::CreateObject();
	    $json_response = $response->Body->OTA_AirRulesRS->FareRuleInfo->Rules;
	    
	    return json_encode($json_response);

	}
}