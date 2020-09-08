<?php
namespace App\Commons;
use App\Commons\TagsGdsHandler;
use DateTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use App\Containers\Amadeus\Tasks\AmadeusAuthTask;
class CommonActions{

  public static function RemoveT($date){
    return str_replace("T", " ", $date);
  }
  public static function Soap2Json($soap){
    $xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $soap);
    return CommonActions::XML2JSON($xml);
  }
  public static function DateDiff($d1,$d2){
    $start_date = new DateTime($d1);
    $since_start = $start_date->diff(new DateTime($d2));
    $minutes = $since_start->days * 24 * 60;
    $minutes += $since_start->h * 60;
    $minutes += $since_start->i;
    return $minutes;
  }
  public static function isRoundTrip($triptype){
    return $triptype == '2';
  }
  public static function CreateObject(){
    return (object) array();
  }
  public static function ErrorCache(){
    $response['error'] = true;
    $response['message'] = 'Expired or invalid TagId';
    return (object) $response;
  }
  
  public static function TimeLeft($gen_date){
    $dt = Carbon::now();
    $gen_date = Carbon::parse($gen_date);
    $time_elapsed = $dt->timestamp - $gen_date->timestamp;
    $time_left = 900 - $time_elapsed;
    return $time_left * 1000;
  }
  
  public static function HourToMins($hour){
    $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $hour);
    sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);
    return $hours * 60 + $minutes + $seconds;
  }
  public static function FormatDate($date, $format = "Y-m-d"){
    return date($format,strtotime($date));
  }
  public static function GetItinerary($tagpu = false){
    if($itn = TagsGdsHandler::GetGdsTag($tagpu)){
      $itn = json_decode($itn->itinerary);
      $itinresp = '';
      return $itn->departure_city . '/' . $itn->destination_city . '/' . date('d-m',strtotime($itn->departure_date)) . '/' . date('d-m',strtotime($itn->return_date)) . '/';
    }else{
      return 'Not found';
    }
  }

  public static function XML2JSON($xml, $cast = false) {
    CommonActions::normalizeSimpleXML(simplexml_load_string($xml,'SimpleXMLElement', LIBXML_NOCDATA), $result);
    if(!$cast){
      return json_encode($result);
    }
    return $result;
  }

  private static function normalizeSimpleXML($obj, &$result) {
      $data = $obj;
      if (is_object($data)) {
          $data = get_object_vars($data);
      }
      if (is_array($data)) {
          foreach ($data as $key => $value) {
              $res = null;
              CommonActions::normalizeSimpleXML($value, $res);
              if (($key == '@attributes') && ($key)) {
                  $result = $res;
              } else {
                  $result[$key] = $res;
              }
          }
      } else {
          $result = $data;
      }
  }  
  public static function getPaxAgeId($date){
    $parsedate = Carbon::parse($date);
    $difference = Carbon::now()->diffInYears($parsedate);
    if ($difference < 22) {
      $paxid = 'menor';
      }elseif ($difference > 22 and $difference < 75){
      $paxid = 'adulto';
      }else {
      $paxid = 'tercera-edad';
    }
    return($paxid);
  }

  private static function cache($name,$payload,$response){
    $identifier = $name.'-' . str_random(25);
    Cache::put($identifier, json_encode($response), 25);
    return TagsGdsHandler::StoreTag($identifier, $payload);
  }
  
  public static function clientInteraction($endpointName,$client){
    $file = 'TEST_CASE AMADEUS';
    $header_log = "

    Servicio ".$endpointName." REQUEST

    ";
    $header_log2 = "

    Servicio ".$endpointName." RESPONSE

    ";
    Storage::disk('cachekiu')->append($file, $header_log . $client->getLastRequest());
    Storage::disk('cachekiu')->append($file, $header_log2 . $client->getLastResponse());
  }

  public static function makeBookErrorLog($itinerary, $response){
    Storage::delete('cachekiu/ERROR_BOOKING');
    $file = 'ERROR_BOOKING';

	  $header_log = "Itinerario: 
	
	    ".json_encode($itinerary)."
	
	    respuesta: 
	
	  ".json_encode($response);
	
	  Storage::disk('cachekiu')->put($file,$header_log);
  }

  public static function currencyName($currencyCode){
    if($currencyCode == '3'){
      $currency = 'VEF';
    }else{
      $currency = 'USD';
    }
    return $currency;
  }
  public static function buildAmadeusClient($currencyName,$state){
    if($currencyName  == 'USD'){
      return (new AmadeusAuthTask())->run($state); //if the attribute is true, the session is stateless
    }else{
      return (new AmadeusAuthTask())->run($state,'MYCV12119'); //if the attribute is true, the session is stateless
    }
  }
}
?> 
