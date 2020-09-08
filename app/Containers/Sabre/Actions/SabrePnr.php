<?php
namespace App\Containers\Sabre\Actions;
use Illuminate\Support\Facades\Input;
use App\Ship\Parents\Actions\Action;
use App\Containers\Sabre\Tasks\AuthSoapTask;
use App\Containers\Sabre\Tasks\RetrieveItineraryTask;
use App\Containers\Sabre\Tasks\AddPassengersDetailsTask;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Commons\CommonActions;


class SabrePnr extends Action{
    public static function Create($data)
    {
        $currency = $data->itinerary->currency;
        $pnr = (new RetrieveItineraryTask)->run($data);
		$paxdetails = (new AddPassengersDetailsTask)->run($data);
        $token = (new AuthSoapTask)->run($currency); //Obtenemos el token (SOAP) de autenticacion con el api de SABRE
        $soapclient = new SACSSoapClient($token); //Creamos un objecto  SAbre Soap Caller
        $itinerary = $soapclient->doCall($pnr,'GetReservationRQ');
        $modify = $soapclient->doCall($paxdetails,'PassengerDetailsRQ');
        $response = CommonActions::Soap2Json($modify);
		$response = json_decode($response);
        if(!isset($response->Body->PassengerDetailsRS->ApplicationResults->Warning)) {
            $response->modified = true; 
            $data->update();           
        }else{
            $response->modified = false ;
        }
        return $response;
  }
}
?>
