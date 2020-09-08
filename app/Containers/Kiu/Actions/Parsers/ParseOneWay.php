<?php

namespace App\Containers\Kiu\Actions\Parsers;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuHandler;
use App\Containers\Kiu\Actions\Requests\PriceRequest;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Storage;

class ParseOneWay extends Action {
	/**
	* @param KIU_AirAvailRS
	*
	* @return  (object) array
	*/
	private static function ConstructFS($segment) {
		$fs = '';

		return '<FlightSegment DepartureDateTime="'.$segment->DepartureDateTime.'" ArrivalDateTime="'.$segment->ArrivalDateTime.'" FlightNumber="'.$segment->FlightNumber.'" ResBookDesigCode="Y" >
		                <DepartureAirport LocationCode="'.$segment->DepartureAirport->LocationCode.'"/>
		                <ArrivalAirport LocationCode="'.$segment->ArrivalAirport->LocationCode.'"/>
		                <MarketingAirline Code="'.$segment->MarketingAirline->CompanyShortName.'"/>
		            </FlightSegment>';
	}
	/**
	* @param KIU_AirAvailRS
	*
	* @return  (object) array
	*/
	public static function MakeOdoXML($segment) {
		// recorrer cada escala
		//$fs = array();
		$fs = '';
		//vuelos con escala
		if (is_array ( $segment )) {
			$total = count($segment);
			print_r($total);
			print_r($segment);
			foreach($segment as $escalakey => $escala) {
				$fs .= ParseOneWay::ConstructFS($escala);
				//$fp[] = KiuParseResponse::MakeFootprint($escala);
				//$segs[] = KiuParseResponse::ConstructBCA($escala);
			}
		//vuelos directos
		} else {
			$fs = ParseOneWay::ConstructFS($segment);
		}
		//'.$class.'
		$odo = 	'<OriginDestinationOption>'
					.$fs.
            	'</OriginDestinationOption>';

        //return $odo;

        Storage::disk('cachekiu')->append('odo.xml', $odo);

	}
	/**
    * @param FlightSegment
    *
    * @return  (object) array
    */
	private static function GetClasses($segment) {
		$vars = KiuParseResponse::GetVars($segment);
		$arr = $vars;
		//print_r($footprint);
		//verifica si tiene mas de una clase
		if (is_array ( $segment->BookingClassAvail )) {

			foreach($segment->BookingClassAvail as $clasekey => $clase) {
				//$arrprice = KiuParseResponse::ConstructPrice($vars,$clase);
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
			//$arrprice = KiuParseResponse::ConstructPrice($vars,$segment->BookingClassAvail);
			//print_r($arrprice);die;
			/*if(property_exists($arrprice, "errorPrice")) {
				$arr->MissingFares[] = array(
					"ResBookDesigCode" => $segment->BookingClassAvail->ResBookDesigCode,
					"ResBookDesigQuantity" => $segment->BookingClassAvail->ResBookDesigQuantity,
					"errorPrice" => $arrprice
				);
			} else {
				$arr->BookingClassAvail[] = $arrprice;
			}*/
		}
		return (object) $arr;
	}

}
?>
