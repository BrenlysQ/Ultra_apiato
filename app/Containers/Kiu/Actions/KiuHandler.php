<?php

namespace App\Containers\Kiu\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Commons\CommonActions;
use Ixudra\Curl\Facades\Curl;
use App\Containers\Kiu\Actions\KiuAuth;
use App\Containers\Kiu\Actions\KiuParseResponse;
use App\Containers\Kiu\Models\FaresCacheModel;
use App\Containers\Kiu\Models\KiuRoutesModel;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use App\Commons\TagsGdsHandler;
use App\Containers\Kiu\Tasks\PricePayloadTask;
use App\Containers\Kiu\Tasks\LocalizableCheckerTask;
use App\Containers\Kiu\Tasks\ListProcessesTask;
use App\Containers\Kiu\Tasks\UpdateProcessStatusTask;
use App\Containers\Kiu\Tasks\AirDemandTicketTask;
use App\Containers\Itineraries\Tasks\VerifyKiuItineries;
use App\Containers\Itineraries\Tasks\DecreaseAirlineBalanceTask;
use App\Containers\Itineraries\Tasks\CreateAirlineBalanceHistoryTask;
use App\Containers\Kiu\Tasks\MakeRequestTask;
use Carbon\Carbon;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;

class KiuHandler extends Action {

  public static function StoreFare($vars,$PricingInfo,$class) {

    $totalfare = $PricingInfo->ItinTotalFare->TotalFare->Amount;
    $CurrencyCode = $PricingInfo->ItinTotalFare->TotalFare->CurrencyCode;
    $curr = KiuHandler::GetCurrency($CurrencyCode);
    $curr = $curr->id;
    $expirationdate = CommonActions::FormatDate($vars->DepartureDateTime,"Y-m-d");
    $footprint = KiuHandler::MakeFootprint($vars,$expirationdate,$CurrencyCode,$class);
    //print_r(json_encode($vars));
    //print_r(json_encode($PricingInfo));

    /*"DepartureAirport" => $segment->DepartureAirport->LocationCode,
      "ArrivalAirport" => $segment->ArrivalAirport->LocationCode,
      "DepartureDateTime" => $segment->DepartureDateTime,
      "ArrivalDateTime" => $segment->ArrivalDateTime,
      "FlightNumber" => $segment->FlightNumber,
      "MarketingAirline" => $segment->MarketingAirline->CompanyShortName*/

    $cache = new FaresCacheModel();
    $cache->expirationdate = $expirationdate;
    $cache->footprint = $footprint;//md5($str)
    $cache->passengertype = $PricingInfo->PTC_FareBreakdowns->PTC_FareBreakdown->PassengerTypeQuantity->Code;
    $cache->class = $class;
    $cache->totalfare = $totalfare;
    $cache->currency = $curr;
    $cache->airpricinginfo = json_encode($PricingInfo);
    $cache->save();
  }
  public static function Cache($reqprice = true, $tagpu = false, $legs = false)
  {
	if(!$legs){
		$legs = json_decode(Input::get('legs'));
	}
    $tag = TagsGdsHandler::GetGdsTag($tagpu);
    if($value = Cache::get($tag->tag_id)){
    $time_left = CommonActions::TimeLeft($tag->gen_date);
    $itinerary = json_decode($tag->itinerary);
      $cache = json_decode($value);
      $out = $cache[$legs[0]->seqnumber - 1]->outbound[0];
      $ret = $cache[$legs[1]->seqnumber - 1]->return[0];
      $object = CommonActions::CreateObject();
      $object->payload = $itinerary;
      $outpl = KiuHandler::designLeg($out);
      $retpl = KiuHandler::designLeg($ret);
      $request = (new PricePayloadTask())->run($outpl,$retpl,$itinerary->currency);
      $response_xml = KiuAuth::MakeRequest($request);
      $response = json_decode(CommonActions::XML2JSON($response_xml));
      $object->fare = CommonActions::CreateObject();
      $object->fare->airpricinginfo = $response->PricedItineraries->PricedItinerary->AirItineraryPricingInfo->ItinTotalFare;
      $price = PriceHandler::PriceBuild($object);
      $flight = array(
        "outbound" => $out,
        "return" => $ret,
        "price" => $price,
      );
      $json = array(
        "flight" => (object) $flight,
        "currency" => $itinerary->currency,
        "time_left" => $time_left,
		"expiration_date" => KiuHandler::getExpDate($time_left)
      );
      return $json;
    }else{
      return json_encode(CommonActions::ErrorCache());
    }
  }
  private static function getExpDate($milseconds){
	$dt = Carbon::now();
	$dt->addSeconds($milseconds / 1000);
	$time = CommonActions::CreateObject();
	$time->date = $dt->toDateTimeString();
	$time->timezone = $dt->tzName;
	return $time;
  }
  public static function StoreKiuRoute() {
    $origin = Input::get('origin');
    $destination = Input::get('destination');
    $newroute = new KiuRoutesModel();
    $newroute->origin = $origin;
    $newroute->destination = $destination;
    $newroute->save();
  }
  public static function GetCurrency($code){
    $currency = Cache::remember('CURRENCY-' . $code, 180, function() use ($code) {
      if($code){
        return CurrenciesHandler::GetByCode($code);
      }
    });
    return $currency;
  }
  /**
    * @param
    *
    * @return
    */
  private static function MakeFootprint($segment,$expirationdate,$CurrencyCode,$class) {
    //CCS MIA AA 1234 12-02-2017
    $footprint = $segment->DepartureAirport . $segment->ArrivalAirport . $segment->MarketingAirline. $segment->FlightNumber. $expirationdate . $CurrencyCode. $class;

/*    $segment->footprint = $footprint;
    $segment->expirationdate = $expirationdate;*/

    return $footprint;
  }

  public static function designLeg($leg){
  $classes = $leg->classes;
  foreach($leg->segments as $index => $segment){
    $response = '
        <FlightSegment DepartureDateTime="' . $segment->dtime . '" ArrivalDateTime="' . $segment->atime . '"
          FlightNumber="' . $segment->flightno . '" ResBookDesigCode="' . $classes[$index] . '" >
          <DepartureAirport LocationCode="' . $segment->origin->IATA . '"/>
          <ArrivalAirport LocationCode="' . $segment->destination->IATA . '"/>
          <MarketingAirline Code="' . $segment->airline->iata . '"/>
        </FlightSegment>';
  }
  return $response;
  }
  public static function checkLocalizables(){
    return (new LocalizableCheckerTask)->run();
  }
  public static function checkProcess(){
    $processes = (new ListProcessesTask)->run();
    if ($processes->count <= 12) {
      return (new UpdateProcessStatusTask)->run();
    }
  }
  public static function demandTicket($req){
    $itinerary = (new VerifyKiuItineries)->run($req->input('itinerary'), $req->input('currency'));
    $verified = (new MakeRequestTask())->run($itinerary);
    if (isset($verified->Error)) {
      return array('Error' => true );
    }
    // else {
    //   $verified = CommonActions::XML2JSON($verified);
    // }
    // dd($verified->TravelItinerary->ItineraryInfo->ReservationItems->Item->Air->Reservation->MarketingAirline);
    $request = (new AirDemandTicketTask)->run($verified, $req->input('currency'));
    $airline = (new DecreaseAirlineBalanceTask)->run($verified->amount,$verified->currency);
    $history = (new CreateAirlineBalanceHistoryTask)->run($verified);
    $response = (new MakeRequestTask())->run($request, false);
    dd($response);
    return $response;
  }
}
?>
