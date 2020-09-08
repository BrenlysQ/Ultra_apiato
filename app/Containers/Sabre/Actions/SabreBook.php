<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use App\Commons\TagsGdsHandler;
use App\Commons\CommonActions;
use App\Containers\Sabre\Tasks\ValPayloadTask;
use App\Containers\Sabre\Tasks\EnhAirPayloadTask;
use App\Containers\Sabre\Tasks\PaxPayloadTask;
use App\Containers\Sabre\Tasks\SegmentsPayloadTask;
use App\Containers\Sabre\Tasks\AuthSoapTask;
use App\Containers\Sabre\Tasks\PassDetPayloadTask;
use App\Containers\UltraApi\Actions\UltraBook;
use Illuminate\Support\Facades\Storage;
use App\Mail\BookErrorMail;

class SabreBook extends Action {
  public function run()
  {
    //$payload = $this->call(ValPayloadTask::class);
    
    if(!$payload = $this->call(ValPayloadTask::class)){ // Valido el payload
      return array(
        'error' => true,
        'message' => 'Invalid payload or tagid'
      );
    }else{
		$currency = $payload->itinstored->currency;
		//dd($currency);
      //return json_encode($payload);
      $paxdetails = $this->call(PaxPayloadTask::class); //Obtenemos un objeto con el payload que contiene el detalle de los pasajeros, SSR; NOMBRES, EMAIL
      $segments = $this->call(SegmentsPayloadTask::class,[$payload,$paxdetails->numberinparty]); //Objeto que contiene los detalles de cada segemento, tanto de ida como de vuelta
      $enhpayload = $this->call(EnhAirPayloadTask::class,[$paxdetails->pritypes,$segments]); //Prepara el payload para EnhancedAirBookRQ
      $token = $this->call(AuthSoapTask::class,[$currency]); //Obtenemos el token (SOAP) de autenticacion con el api de SABRE
      $soapclient = new SACSSoapClient($token); //Creamos un objecto  SAbre Soap Caller
      $soapclient->setLastInFlow(false);
      //Ejecutamos el API EnhancedAirBookRQ
      $response = $soapclient->doCall($enhpayload,'EnhancedAirBookRQ');
      //En la respuesta a enviar guardamos tanto el Payload como la respuesta de EnhancedAirBookRQ
      $resp['EnhancedAirBookRQ'] = $enhpayload;
      $resp['EnhancedAirBookRS'] = $response;
	  $json = CommonActions::Soap2Json($response);
	  //print_r($json); die;
      //dd($resp);
	  //TODO: VALIDAR LA PRIMERA RESPUESTA
      $passdet = $this->call(PassDetPayloadTask::class,[$paxdetails]); //Preparamos el payload a enviar a PassengerDetailsRQ (cierra PNR y deuelve localizador)
		//echo $passdet; die;
      $response = $soapclient->doCall($passdet,'PassengerDetailsRQ'); // Llamo a PassengerDetailsRQ
      //En la respuesta a enviar guardamos tanto el Payload como la respuesta de PassengerDetailsRQ
      $resp['PassengerDetailsRQ'] = $passdet;
      $resp['PassengerDetailsRS'] = $response;
	  //Storage::disk('cachekiu')->append('ERCHIVO',json_encode($resp));
	  $json = json_decode(CommonActions::Soap2Json($response))->Body->PassengerDetailsRS;
	  //echo $json; die;
	  //return $json;
	  //print_r(); die;
      $itinid =  false;
	  if(property_exists($json,'ItineraryRef')){
		  $itinid =  $json->ItineraryRef->ID;
	  }
      if($itinid){
        $data = CommonActions::CreateObject();
        $data->itinerary = $payload->itinstored;
		$data->freelance = $paxdetails->freelance;
        $data->odo = CommonActions::CreateObject();
        $data->itineraryid = $itinid;
    	$data->odo->outbound = $payload->odo->outboundflight;
    	$data->odo->return = $payload->odo->returnflight;
        $data->odo->price = $payload->odo->price;
        $resp = UltraBook::Book($data);
      }else{
        $itinerary = $payload->itinstored;
        CommonActions::makeBookErrorLog($itinerary,$response);
        Mail::to('plusultradesarrollo@gmail.com')->send(new BookErrorMail($itinerary));
        $resp = array(
          'error' => true,
          'message' => 'ItineraryRef unavailable.'
        );
      }
      return $resp;
    }

  }
}
 ?>
