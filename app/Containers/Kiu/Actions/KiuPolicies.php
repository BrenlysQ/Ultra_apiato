<?php

namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuParseResponse;

class KiuPolicies extends Action{
	
	private static $token;
	public static function getKiuPolicies(){
		
		$data = (object) array (
			"departure_date" => Input::get('DepartureDateTime'),
			"destination_code" => Input::get('DestinationCode'),
			"airline" => Input::get('Code'),
			"location_code" => Input::get('OriginLocation'),
			"class" => Input::get('FareBasis')
		);

		$TimeStamp = date("c");

	    $request='<?xml version="1.0" encoding="UTF-8"?> 
	                <KIU_AirRulesRQ EchoToken="'.getenv('KIU_ECHOTOKEN').'" TimeStamp="'.$TimeStamp.'" Target="'.getenv('KIU_TARGET').'" Version="3.0" SequenceNmbr="'.getenv('KIU_SEQNMBR').'" PrimaryLangID="en">    
	                	<POS>        
	                		<Source AgentSine="'.getenv('KIU_USERID').'" TerminalID="'.getenv('KIU_TERMINALID').'" ISOCountry="VE"/>    
	                	</POS>
	                    	<RuleReqInfo FareNumber="61244">
	                       		<FareReference>' . $data->class . '</FareReference>        
	                       		<MarketingAirline Code="' . $data->airline . '"/>        
	                       		<DepartureAirport LocationCode="' . $data->location_code . '"/>        
	                       		<ArrivalAirport LocationCode="' . $data->destination_code . '"/>        
	                       		<DepartureDate>' . $data->departure_date . '</DepartureDate>    
	                    	</RuleReqInfo> 
	                </KIU_AirRulesRQ>';
        
        $response = KiuAuth::MakeRequest($request);
        return json_decode(CommonActions::XML2JSON($response));

	}
}