<?php

namespace App\Containers\Insurance\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\CommonActions;
use App\Commons\TagsGdsHandler;
use App\Containers\Insurance\Tasks\ApiLogin;
use App\Containers\Insurance\Tasks\MakeRequest;
use App\Containers\Insurance\Tasks\GetPricePayload;
use App\Containers\Insurance\Tasks\GetCoverage;
use App\Containers\Insurance\Tasks\EmitsCotTask;
use App\Containers\UltraApi\Actions\UltraBook;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use App\Containers\Insurance\Tasks\CreateVoucherTask;
use App\Containers\Insurance\Tasks\GetQuoteTask;
use App\Containers\Insurance\Tasks\GetBestSellersTask;
use App\Containers\Insurance\Tasks\GetPaxesTypeCountTask;
use App\Containers\Insurance\Tasks\GetCotizatedCountriesTask;
use App\Containers\Insurance\Tasks\GetTimelineCotizationsTask;
use App\Containers\Insurance\Tasks\GetPaxTypeReportTask;
use App\Containers\Insurance\Tasks\GetTimelineSellsReportTask;
use Carbon\Carbon;
use App\Containers\Insurance\Actions\PriceHandler;
class InsuranceHandler extends Action {

  public static function GetPlans() {

    //$plans = (new MakeRequest())->run('/list/plan?token=' . $token);
  //print_r($plans); die;
    //return $plans;
  $name = md5('plan_list');
    return Cache::remember($name, 1500000, function (){
    $token = (new ApiLogin())->run();
        $plans = (new MakeRequest())->run('/list/plan?token=' . $token);
        return $plans;
    });
  }

  public static function GenerateCot(){
    $dob = Input::get('dob');
    $tagid = Input::get('tagid');
    $cache = TagsGdsHandler::GetGdsTag($tagid);
    $itinerary = json_decode($cache->itinerary);
    $token = (new ApiLogin())->run();
    $data = (new GetPricePayload())->run($itinerary, $dob);
    $price = (new MakeRequest())->run('/quotation/create?token=' . $token, $data);
    $response = array();
    foreach ($price->body->quotation->plans as $key => $plan){
      $planret = CommonActions::CreateObject();
      $planret->name = $plan->name;
      $planret->price = $plan->price->USD;
      $planret->id_plan = $plan->id;
      $planret->id_cot = $price->body->quotation->id;
      $response[$key] = $planret;
    }
      return json_encode($response);
  }
  public static function BookInsurance(){
	  $paxes = (object) Input::get('pax_data');
		$quotation = (object) Input::get('quotation');
		//dd($quotation);
	  return UltraBook::insuranceBook($paxes,$quotation);
  }
  public static function GetQuotation(){
    /*$dob = Input::get('dob');
    $tagid = Input::get('tagid');
    $cache = TagsGdsHandler::GetGdsTag($tagid);
	//$ages = Input::get('ages');
    $itinerary = CommonActions::CreateObject();*/
    $token = (new ApiLogin())->run();
    $data = (new GetQuoteTask())->run();
	//print_r($data); die;
    $price = (new MakeRequest())->run('/quotation/create?token=' . $token, $data);
    $response = array();
	//print_r($price); die;
	$currency = Input::get('cu');
    foreach ($price->body->quotation->plans as $key => $plan){
      $planret = CommonActions::CreateObject();
      $planret->name = $plan->name;
      $planret->price = PriceHandler::PriceBuild($plan->price->USD, $currency);
      $planret->id_plan = $plan->id;
      $planret->id_cot = $price->body->quotation->id;
	  $planret->currency = $currency;
      $response[$key] = $planret;
    }
      return json_encode($response);
  }

  public static function emiteCot($invoice){
    //task que va a emitir la cotizacion
    $token = (new ApiLogin())->run();
    $data = (new EmitsCotTask())->run($invoice);
  //print_r($data); die;
    $response = (new MakeRequest())->run('/voucher/create?token=' . $token, $data);
    $infoplan = InsuranceHandler::GetPlans();
    $invoiceable = (new CreateVoucherTask())->run($invoice,$response,$infoplan);
    //dd($invoiceable);
    return $invoiceable;
  }

  public static function ViewCoverage(){
    $token = (new ApiLogin())->run();
    $data = (new GetCoverage())->run();
    $response = (new MakeRequest())->run('/list/plan/find?token=' . $token, $data);
    return json_encode($response->body->coverage);
  }

  public static function getInsuCharts(){
    $sellers = (new GetBestSellersTask())->run();
    $paxcount = (new GetPaxesTypeCountTask())->run();
    $dep_countries = (new GetCotizatedCountries())->run('departure_city');
    $des_countries = (new GetCotizatedCountries())->run('destination_city');
    $timeline = (new GetTimelineCotizationsTask())->run();
    $response = array(
      'sellers' => $sellers,
      'paxcount' => $paxcount,
      'dep_countries' => $dep_countries,
      'des_countries' => $des_countries,
      'timeline' => $timeline
    );
    return $response;
  }
  public static function getInsuranceReport($req){
    $type = $req->input('type');
    if ($type == 'pax') {
      $response = (new GetPaxTypeReportTask())->run($req);
    } elseif($type == 'sells') {
      $response = (new GetTimelineSellsReportTask())->run($req);
    }
    return $response;
  }
}
