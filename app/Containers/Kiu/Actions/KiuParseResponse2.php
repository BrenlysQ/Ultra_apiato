<?php

namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuHandler;
use App\Containers\Kiu\Actions\Requests\PriceRequest;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Storage;

class KiuParseResponse extends Action {
  
	/**
	* @param FlightSegment
	*
	* @return  (object) array
	*/
	private static function ConstructPrice($vars,$clase) {

		print_r($vars);print_r($clase);
		// Descarta la clase L y R y las que son = 0
		if ( (is_numeric ( $clase->ResBookDesigQuantity )) && ($clase->ResBookDesigQuantity > 0) ) {
			//obtiene el AirPrice de la clase enviada
			$priceResponse = PriceRequest::GetPrice($vars,$clase->ResBookDesigCode);
			//print_r($priceResponse);

			if (property_exists($priceResponse, "PricedItineraries")) {
				$PricingInfo = $priceResponse->PricedItineraries->PricedItinerary->AirItineraryPricingInfo;
				KiuHandler::StoreFare($vars,$PricingInfo,$clase->ResBookDesigCode);

				return (object) array(
					"ResBookDesigCode" => $clase->ResBookDesigCode,
					"ResBookDesigQuantity" => $clase->ResBookDesigQuantity,
					"AirItineraryPricingInfo" => $PricingInfo
				);
				
			} else {
				return (object) array(
				    "errorPrice" => $priceResponse,
				    "clase" => $clase
				);
			}
		} else {
			return (object) array(
			    "errorPrice" => 'Es clase L, R o = 0',
			    "clase" => $clase
			);
		}

	}

	/**
	* @param FlightSegment
	*
	* @return  (object) array
	*/
	private static function GetVars($segment) {
		return (object) array(
			"DepartureAirport" => $segment->DepartureAirport->LocationCode,
		    "ArrivalAirport" => $segment->ArrivalAirport->LocationCode,
		    "DepartureDateTime" => $segment->DepartureDateTime,
		    "ArrivalDateTime" => $segment->ArrivalDateTime,
		    "FlightNumber" => $segment->FlightNumber,
		    "MarketingAirline" => $segment->MarketingAirline->CompanyShortName
		);
	}

  	/**
    * @param FlightSegment
    *
    * @return  (object) array
    */
	private static function ConstructBCA($segment) {
		$vars = KiuParseResponse::GetVars($segment);
		$arr = $vars;
		//print_r($footprint);
			//verifica si tiene mas de una clase
		if (is_array ( $segment->BookingClassAvail )) {

			foreach($segment->BookingClassAvail as $clasekey => $clase) {
				$arrprice = KiuParseResponse::ConstructPrice($vars,$clase);
				//print_r($arrprice);//die;
				
				if(property_exists($arrprice, "errorPrice")){
					$arr->MissingFares[] = array(
						"ResBookDesigCode" => $clase->ResBookDesigCode,
						"ResBookDesigQuantity" => $clase->ResBookDesigQuantity,
						"errorPrice" => $arrprice->errorPrice
					);
				} else {
					$arr->BookingClassAvail[] = $arrprice;
				}
			}
		} else {
			$arrprice = KiuParseResponse::ConstructPrice($vars,$segment->BookingClassAvail);
			//print_r($arrprice);die;
			if(property_exists($arrprice, "errorPrice")) {
				$arr->MissingFares[] = array(
					"ResBookDesigCode" => $segment->BookingClassAvail->ResBookDesigCode,
					"ResBookDesigQuantity" => $segment->BookingClassAvail->ResBookDesigQuantity,
					"errorPrice" => $arrprice
				);
			} else {
				$arr->BookingClassAvail[] = $arrprice;
			}
		}
		return (object) $arr;
	}
  
	/**
	* @param FlightSegment
	*
	* @return  (object) array
	*/
	private static function ConstructSegment($segment) {
		//print_r(json_encode($segment));
		//verifica si tiene escala
		if (is_array ( $segment )) {
			$segs = array();
			foreach($segment as $escalakey => $escala) {
				$segs[] = KiuParseResponse::ConstructBCA($escala);
			}
			return $segs;
		} else {
			//print_r(json_encode($segment)); 
			return KiuParseResponse::ConstructBCA($segment);
		}
	}	  		
			  		

	/**
	* @param KIU_AirAvailRS
	*
	* @return  (object) array
	*/
	public static function AirAvailRS($json,$payload) {
		//print_r($json); die;

		set_time_limit(0);
		//$avail = array();

		// ciclo para recorrer la [0]:ida y [1]:vuelta
		$idas = $json->OriginDestinationInformation[0];
		$vueltas = $json->OriginDestinationInformation[1];
		KiuParseResponse::init_odi($idas,$vueltas);

		//print_r(json_encode($idas);
		//print_r(json_encode($vueltas);

		/*foreach($json->OriginDestinationInformation as $ruta => $odi) {  		
			// verifica si esta vacio el odopts
		  	if ($odi->OriginDestinationOptions == null) {
			  	$avail[$ruta] = null;
		  	} else {
		  		$options = array();
		  		foreach($odi->OriginDestinationOptions as $key => $odos) {
		  			//Segment
		  			if (is_array ( $odos )) {
		  				foreach($odos as $odo){
		  					$options[] = KiuParseResponse::ConstructSegment($odo->FlightSegment);
		  					
		  				}
		  				//print_r(json_encode($odos)); die;
		  			// aca hay una sola opcion de vuelo
		  			} else {
		  				$options[] = KiuParseResponse::ConstructSegment($odos->FlightSegment);
		  			}
		  		}
		  		
			  	$avail[$ruta] = $options;
		  	}
		}
		$TimeStamp = date("c");
		//print_r(json_encode($avail));
		$footprint = $payload->departure_city . $payload->destination_city . $TimeStamp;
		Storage::disk('cachekiu')->put($footprint.'.json', json_encode($avail));*/
	}

	/**
	* @param KIU_AirAvailRS
	*
	* @return  (object) array
	*/
	private static function init_odi($idas,$vueltas) {

		$avail = array();
		//si el arreglo de idas esta vacio
		if ($idas->OriginDestinationOptions == null) {
		  	$avail[0] = null;
		//si el arreglo de idas esta lleno
	  	} else {
	  		//si el arreglo de vueltas esta vacio
	  		if ($vueltas->OriginDestinationOptions == null) {
		  		$avail[1] = null;
		  	//si el arreglo de vueltas esta lleno
		  	} else {
		  		
		  	}
	  	}

	  	//print_r(json_encode($avail));

	}

}
?>
