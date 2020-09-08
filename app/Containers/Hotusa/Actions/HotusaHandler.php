<?php

namespace App\Containers\Hotusa\Actions;
use App\Commons\TagsGdsHandler;
use App\Containers\Hotusa\Actions\HotelOptions;
use App\Containers\Hotusa\Actions\PrebookHandler;
use App\Containers\Hotusa\Actions\ConfbookHandler;
use App\Containers\Hotusa\Actions\HotelInformationHandler;
use App\Containers\Hotusa\Actions\CardInformationHandler;
use App\Containers\Hotusa\Actions\BookingBonusHandler;
use Illuminate\Support\Facades\Cache;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Input;
use Ixudra\Curl\Facades\Curl;
use App\Commons\CommonActions;
use App\Containers\Hotusa\Tasks\GetBookingCancellationInformationsTask;
use App\Containers\Hotusa\Tasks\GetBookingInfoTask;
use App\Containers\Hotusa\Tasks\MakeRequestTask;
use App\Containers\Hotusa\Tasks\GetProvinciesTask;
use App\Containers\Hotusa\Tasks\GetCountriesTask;
use App\Containers\Hotusa\Tasks\GetDirectoriesTask;
use App\Containers\Hotusa\Tasks\GetHotelAvailability;
use App\Containers\Hotusa\Tasks\GetHotelListTask;
use App\Containers\Hotusa\Tasks\GetReservationsTask;
use App\Containers\Hotusa\Tasks\CancelBookingTask;
use App\Containers\Hotusa\Tasks\CancelReservationTask;
use App\Containers\Hotusa\Tasks\ConfirmBookingTask;
use App\Containers\Hotusa\Tasks\GetObservationsTask;
use App\Containers\Hotusa\Tasks\CreateRoomOptionTask;
use App\Containers\Hotusa\Tasks\CreateLinsTask;
use App\Containers\Hotusa\Tasks\GetHotelsInformationsTask;
use App\Containers\Hotusa\Tasks\GetBonusBookingInfoTask;
use App\Containers\Hotusa\Tasks\CancellationFeesTask;
use App\Containers\Hotusa\Tasks\PayloadTask;
use App\Containers\Hotusa\Tasks\RoomsDataTask;
use App\Containers\Hotusa\Models\HotusaProvincies;
use App\Containers\Hotusa\Models\HotusaCountries;
use App\Containers\Hotusa\Models\HotusaDirectories;
use App\Containers\UltraApi\Actions\UltraBook;
use Carbon\Carbon;
class HotusaHandler extends Action {

  public static function getHotelAvailabilities(){
	  set_time_limit(0);
	  //echo 'ddjdjdjd'; die;
    $rooms_data = (new RoomsDataTask())->run();
    //dd($rooms_data);
    $payload = (new PayloadTask())->run();
    $request = (new GetHotelAvailability())->run($payload, $rooms_data);
    //echo $request; die;
    $response = (new MakeRequestTask())->run($request);
    //print_r($response); die;
    if ($response->param->hotls->num == 0)
      return (object) array(
        'errorcode' => 200
      );
    else {
      $tagpu = HotusaHandler::cache($payload,$response);
      $parsed_response = new HotelOptions($response,$tagpu,$payload);
      return json_encode($parsed_response);
    }
  }

  private static function cache($payload,$response){
    $identifier = 'HOTUSA-' . str_random(25);
    Cache::put($identifier, json_encode($response), 25);
    return TagsGdsHandler::StoreTag($identifier, $payload);
  }

  private static function getHotelsInformations($codigo_cobol,$idioma){
  	$name = md5('hotelinfo-' . $codigo_cobol . '-' . $idioma);
  	return Cache::remember($name, 1, function () use ($codigo_cobol,$idioma){
  		$request = (new GetHotelsInformationsTask())->run($codigo_cobol,$idioma);
  		$response = (new MakeRequestTask())->run($request);
  		return $response->parametros->hotel;
  	});
  }

  private static function getObservations($codigo_cobol,$entrada,$salida){
  	$name = md5('hotelobs-' . $codigo_cobol . '-' . $entrada . '-' . $salida);
  	return Cache::remember($name, 1, function () use ($codigo_cobol,$entrada,$salida){
  		$request = (new GetObservationsTask())->run($codigo_cobol,$entrada,$salida);
  		$response = (new MakeRequestTask())->run($request);
  		return $response->parametros->hotel->observaciones;
  	});

  }
	private static function buildHotelFromTagpu(){
		$seqnumber = Input::get('seqnumber');
		$taggds = TagsGdsHandler::GetGdsTag();
		$payload = json_decode($taggds->itinerary);
		$cache = json_decode(Cache::get($taggds->tag_id));

		//dd($taggds);
		if(is_array($cache->param->hotls->hot)){
		  $hotel = $cache->param->hotls->hot[$seqnumber - 1];
		}else{
		  $hotel = $cache->param->hotls->hot;
		}
		//print_r($hotel); die;
		//$hoteloption = new HotelHandler($hotel,0);
		$option = new HotelHandler($hotel, $seqnumber -1, true, $payload);
		return $option;
	}
	public static function cancellation_fees(){
		$loc = Input::get('n_localizador');
		$request = (new CancellationFeesTask())->run($loc);
		$response = (new MakeRequestTask())->run($request);
		print_r($response); die;
	}
  public static function getBookingCancellationInformations(){
	$tagpu = Input::get('tagpu');
	$seqnumbers = explode('-',Input::get('seqnumbers'));
	//print_r($seqnumbers); die;
	$hotel = HotusaHandler::buildHotelFromTagpu();
	//print_r($hotel->rooms[0]); die;
	$room_index = $seqnumbers[0];
	$rooms = array(
		$hotel->rooms[0][$room_index]
	);
	//$room$hotel->rooms[0][$seqnumbers[0]];
	$seqnumbers = array(
		array(
			0,
			$seqnumbers[1],
			$seqnumbers[2]
		)
	);
	$lins = (new CreateLinsTask())->run($rooms,$seqnumbers);

	//print_r($hotel); die;
    $request = (new GetBookingCancellationInformationsTask())->run($lins,$hotel->cobol_code);

    $response = (new MakeRequestTask())->run($request);
	//print_r($response); die;
    return $response->parametros;
  }
  public static function hotelInformation(){
    $cobol_code = Input::get('cobol_code');
    $date_in = Input::get('date_in');
    $date_out = Input::get('date_out');
    $information = HotusaHandler::getHotelsInformations($cobol_code,1);
    $observations = HotusaHandler::getObservations($cobol_code,$date_in,$date_out);
    //dd($information,$observations);
  	$response = CommonActions::CreateObject();
  	$response->hotel_information = $information;
  	$response->hotel_observations = $observations;
  	$parsed_response = new HotelInformationHandler($response);
  	//dd($response);
    return $parsed_response;
  	//return $response;
  }
  public static function bookinformation(){
    $seqnumber = Input::get('seqnumber');
    $taggds = TagsGdsHandler::GetGdsTag();
	//dd($taggds);
	$time_left = CommonActions::TimeLeft($taggds->gen_date);
    $payload = json_decode($taggds->itinerary);
    $cache = json_decode(Cache::get($taggds->tag_id));
    $hotel = $cache->param->hotls->hot[$seqnumber -1];
    //print_r($hotel); die;
    $option = new HotelHandler($hotel, $seqnumber -1, true, $payload);
    //print_r($hotel); die;
    $rooms = (new CreateRoomOptionTask())->run($hotel->res->pax, $payload->rooms_data);
    //$rooms = $this->call(CreateRoomOptionTask::class,[$hotel->res->pax, $payload->rooms_data]);
    //print_r($rooms); die;
    $information = HotusaHandler::getHotelsInformations($option->cobol_code,$payload->idioma);
    $observations = HotusaHandler::getObservations($option->cobol_code,$payload->fechaentrada,$payload->fechasalida);
    $toparse = (object) array (
      "hotel_information" => $information,
      "hotel_observations" => $observations
    );
    $parsed_response = new HotelInformationHandler($toparse);
    $parsed_response->option = $option;
    $parsed_response->rooms = $rooms;
    (property_exists($hotel,'city_tax')) ? $parsed_response->city_tax = $hotel->city_tax : $parsed_response->city_tax = '';
    //$parsed_response->city_tax = $hotel->city_tax;
    $parsed_response->date_in = $payload->fechaentrada;
    $parsed_response->date_out = $payload->fechasalida;
	$json = array(
		"hotel" => $parsed_response,
		"expiration_date" => HotusaHandler::getExpDate($time_left),
		"itinerary" => $payload,
	);
    return $json;
  }
  private static function getExpDate($milseconds){
	$dt = Carbon::now();
	$dt->addSeconds($milseconds / 1000);
	$time = CommonActions::CreateObject();
	$time->date = $dt->toDateTimeString();
	$time->timezone = $dt->tzName;
	return $time;
  }
  public static function getBundledInformations(){
    $seqnumber = Input::get('seqnumber');
    $taggds = TagsGdsHandler::GetGdsTag();
    $payload = json_decode($taggds->itinerary);
    $cache = json_decode(Cache::get($taggds->tag_id));

    dd($taggds);
    if(is_array($cache->param->hotls->hot)){
      $hotel = $cache->param->hotls->hot[$seqnumber - 1];
    }else{
      $hotel = $cache->param->hotls->hot;
    }
	//print_r($hotel); die;
	$hoteloption = new HotelHandler($hotel,0);
	//dd($hotel);
    $information = HotusaHandler::getHotelsInformations($hotel->cod,$payload->idioma);

    $observations = HotusaHandler::getObservations($hotel->cod,$hotel->fen,$hotel->fsa);
	//dd($observations);
    /*if(is_array($hotel->res->pax->hab)){
      $habitacion = $hotel->res->pax->hab[$seqnumber_hab - 1];
    }else{
      $habitacion = $hotel->res->pax->hab;
    }*/
    ///$cancell_info = HotusaHandler::getBookingCancellationInformations($hotel->cod,$habitacion->reg->lin);
    $response = (object) array (
      "hotel_information" => $information,
      "hotel_observations" => $observations,
      "hotel" => $hoteloption
    );

    $parsed_response = new HotelInformationHandler($response);
	//dd($response);
    return $parsed_response;
  }

  public static function getReservations($confirmar = false){

    //Este es el codigo verdadero de la reserva
    $tagpu = Input::get('tagpu');
    $seqnumber = Input::get('seqnumber');
    /*$seqnumber_hab = Input::get('seqnumber_hab');
    $seqnumber_reg = Input::get('seqnumber_reg');
    $bonusb = CommonActions::CreateObject();
    $bonusb->language = Input::get('language',1);*/

    $taggds = TagsGdsHandler::GetGdsTag($tagpu);
	//print_r($taggds); die;
    $cache = json_decode(Cache::get($taggds->tag_id));
	$payload = json_decode($taggds->itinerary);
	//dd($cache);


    if(is_array($cache->param->hotls->hot)){
      $hotel = $cache->param->hotls->hot[$seqnumber - 1];
    }else{
      $hotel = $cache->param->hotls->hot;
    }
	$option = new HotelHandler($hotel, $seqnumber -1, true, $payload);

	$option->seqnumbers = Input::get('seqnumbers');
	$lins = (new CreateLinsTask())->run($option->rooms[0]);
	//print_r($lins); die;
    $request = (new GetReservationsTask())->run($hotel->cod,$lins);


    $prebook = (new MakeRequestTask())->run($request);
/*
    $request2 = (new ConfirmBookingTask())->run($prebook->parametros->n_localizador);
    $confbook = (new MakeRequestTask())->run($request2);

    $bonusb->booking_id = $prebook->parametros->n_localizador;
    $request3 = (new GetBonusBookingInfoTask())->run($bonusb);
    $bookingbonus = (new MakeRequestTask())->run($request);



    $prebook = CommonActions::CreateObject();
    $prebook->parametros = CommonActions::CreateObject();

    $prebook->parametros->estado = '00';
    $prebook->parametros->n_localizador = '28209895';
    $prebook->parametros->importe_total_reserva = '10.06';
    $prebook->parametros->divisa_total = 'DO';
    $prebook->parametros->n_mensaje = '00000000';
    $prebook->parametros->n_expediente = 'AB1245';
    $prebook->parametros->observaciones = 'The client needs a baby cot.Vistas a la ciudad';
    $prebook->parametros->datos = null;

    $confbook = CommonActions::CreateObject();
    $confbook->parametros = CommonActions::CreateObject();

    $confbook->parametros->estado= '00';
    $confbook->parametros->localizador= '28211147';
    $confbook->parametros->localizador_corto= '23885805';

    $parsed_prebook = new PrebookHandler($prebook->parametros);
    $parsed_confbook = new ConfbookHandler($confbook->parametros);

    $bookingbonus = CommonActions::CreateObject();
    $bookingbonus->parametros = CommonActions::CreateObject();
    $bookingbonus->parametros->info = 'prueba';
	*/
	$parsed_prebook = new PrebookHandler($prebook->parametros);
    if (($parsed_prebook->status == '00')) {
      return UltraBook::hotelBooking($parsed_prebook,$option);
    }elseif (($parsed_prebook->status == '00') && ($parsed_confbook->status != '00')) {
      return 'No confirmó la reserva';
    }else{
		echo 'ERROR: ';
		print_r($parsed_prebook);
		die;
      return $parsed_prebook;
    }

  }
	public static function cancel_reservation(){
		$loc_long = Input::get('loc_long');
		$loc_short = Input::get('loc_short');
		$request = (new CancelReservationTask())->run($loc_long,$loc_short);
		$response = (new MakeRequestTask())->run($request);
		print_r($response); die;
		return $response;
	}
  public static function bookingBonusInfo(){
    $data = CommonActions::CreateObject();
    $data->language = Input::get('language');
    $data->booking_id = Input::get('booking_id');

    $request = (new GetBonusBookingInfoTask())->run($data);
    $response = (new MakeRequestTask())->run($request);

    $parsed_response = new BookingBonusHandler($response->parametros);
    return json_encode($response->parametros);
  }

  public static function cancelBookings(){
    $n_localizador = Input::get('n_localizador');
    $request = (new CancelBookingTask())->run($n_localizador);
    $response = (new MakeRequestTask())->run($request);
    return $response;
  }

  public static function infoBooking(){
    $booking_id = Input::get('booking_id');
    $request = (new GetBookingInfoTask())->run($booking_id);
    $response = (new MakeRequestTask())->run($request);
    return json_encode($response);
  }

  public static function cardsInformation(){
	$rooms_data = (new RoomsDataTask())->run();
    $payload = (new PayloadTask())->run();
    $availability_request = (new GetHotelAvailability())->run($payload, $rooms_data);
    $availability_response = (new MakeRequestTask())->run($availability_request, $payload);
    if ($availability_response->param->hotls->num == 0)
      echo 'No hay Disponibilidad';
    else {
      $tagpu = HotusaHandler::cache($payload,$availability_response);
      $parsed_response = new HotelOptions($availability_response,$tagpu,$payload);

	 return json_encode($parsed_response);
      /* Codigo que genera un numero aleatorio*/
      $random = rand (0 , (count($parsed_response->options) - 1));
      /* Codigo que genera un numero aleatorio*/
      $information = HotusaHandler::getHotelsInformations($parsed_response->options[$random]->cobol_code,$payload->idioma);
      $response_information = (object) array (
        "hotel_information" => json_decode($information)
      );
      $parsed_information = new CardInformationHandler($response_information);
      $response = (object) array (
        "hotel_avail" => $parsed_response->options[$random],
        "hotel_information" => $parsed_information
      );

      return json_encode($response);
    }

  }

  public static function getProvinces(){
      $request = (new GetProvinciesTask())->run();
      $response = (new MakeRequestTask())->run($request);
      foreach ($response->parametros->provincias->provincia as $provincia ){
        $provincies = new HotusaProvincies();
        $provincies->country_code = $provincia->codigo_pais;
        $provincies->provincie_code = $provincia->codigo_provincia;
        $provincies->provincie_name = $provincia->nombre_provincia;
        $provincies->save();
      }
      echo 'Almacenados Exitosamente';
  }

  public static function getCountries(){
      $request = (new GetCountriesTask())->run();
      $response = (new MakeRequestTask())->run($request);
      foreach ($response->parametros->paises->pais as $pais ){
        $countries = new HotusaCountries();
        $countries->country_code = $pais->codigo_pais;
        $countries->country_name = $pais->nombre_pais;
        $countries->save();
      }
      echo 'Almacenados Exitosamente';
  }

  public static function getHotelsList(){
      $request = (new GetHotelListTask())->run();
      $response = (new MakeRequestTask())->run($request);
      dd($response);
  }
	public static function confirmBooking(){
		$loc = Input::get('n_localizador');
		$request = (new ConfirmBookingTask())->run($loc);
		$response = (new MakeRequestTask())->run($request);
		dd($response);
	}
  public static function getDirectories(){
    set_time_limit(0);
    ini_set('memory_limit','512M');
    $provincies = HotusaProvincies::where([
                ['id', '>=', Input::get('since')],
                ['id', '<=', Input::get('to')],
              ])->get();
      foreach ($provincies as $province){
        //$province->province_code
        $request = (new GetDirectoriesTask())->run($province->province_code);
        $response = (new MakeRequestTask())->run($request);
        if($response->parametros->hoteles->num != '0'){
          if(is_array($response->parametros->hoteles->hotel)){
            foreach($response->parametros->hoteles->hotel as $hotel){
              $exist = HotusaDirectories::where('cobol_code', '=', $hotel->codigo_cobol)->first();
              if(!$exist){
                $directory = new HotusaDirectories();
                $directory->hotel_name = $hotel->nombre_h;
                $directory->country_code = $province->country_code;
                $directory->province_code = $province->province_code;
                $directory->province_name = $province->province_name;
                $directory->cobol_code =  $hotel->codigo_cobol;
                $directory->save();
              }else{
                $exist->hotel_name = $hotel->nombre_h;
                $exist->country_code = $province->country_code;
                $exist->province_code = $province->province_code;
                $exist->province_name = $province->province_name;
                $exist->cobol_code =  $hotel->codigo_cobol;
                $exist->update();
              }
              print_r($province->id.' ');
            }
          }else{
            $exist = HotusaDirectories::where('cobol_code', '=', $response->parametros->hoteles->hotel->codigo_cobol)->first();
            if(!$exist){
              $directory = new HotusaDirectories();
              $directory->hotel_name = $response->parametros->hoteles->hotel->nombre_h;
              $directory->country_code = $province->country_code;
              $directory->province_code = $province->province_code;
              $directory->province_name = $province->province_name;
              $directory->cobol_code =  $response->parametros->hoteles->hotel->codigo_cobol;
              $directory->save();
            }else{
              $exist->hotel_name = $response->parametros->hoteles->hotel->nombre_h;
              $exist->country_code = $province->country_code;
              $exist->province_code = $province->province_code;
              $exist->province_name = $province->province_name;
              $exist->cobol_code =  $response->parametros->hoteles->hotel->codigo_cobol;
              $exist->update();
            }
            print_r($province->id.' ');
          }
        }
      }
    return 'Inventario de Hoteles, almacenados';
  }
  /*
    Algortimo para contar los hoteles por provincia, descomentar esto, y comentar el metodo arriba

    public static function getDirectories(){
    set_time_limit(0);
    $provincies = HotusaProvincies::where([
                ['id', '>=', 1],
                ['id', '<=', 1000],
              ])->get();
      foreach ($provincies as $province){
        $request = (new GetDirectoriesTask())->run($province->province_code);
        $response = (new MakeRequestTask())->run($request);
        if($response->parametros->hoteles->num != 0){
          $province = HotusaProvincies::find($province->id);
          $province->number_of_hotels = $response->parametros->hoteles->num;
          $province->update();
        }
      }
    return 'Inventario de Hoteles, almacenados';
  }
  */

}
