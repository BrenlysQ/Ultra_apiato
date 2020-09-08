<?php

namespace App\Containers\Itineraries\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use App\Containers\Itineraries\Models\OfficeItineraryModel;
use App\Containers\Itineraries\Tasks\VerifyKiuItineries;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use App\Containers\Kiu\Tasks\MakeRequestTask;
use App\Containers\Itineraries\Tasks\DecreaseAirlineBalanceTask;
use App\Containers\Itineraries\Tasks\CreateAirlineBalanceHistoryTask;
use Carbon\Carbon;


class CheckOfficeItinerariesTask extends Task
{

  public function run($itinerary)
  {
   
      $request = (new VerifyKiuItineries)->run($itinerary->localizable, $itinerary->currency);
      $response = (new MakeRequestTask())->run($request);
      $today = Carbon::now(-4);
      $created = Carbon::parse($itinerary->created_at);
      if ($response->TravelItinerary->ItineraryInfo->Ticketing->TicketingStatus == 1) {
        //Estatus 1 es que está pendiente de emisión
        //Verificamos si la diferencia entre horas del registro a la del momento en el que se corre el proceso con la hora la establecida para cancelar o mantener el itinerario activo.
        if ($today->diffInHours($created) > getenv('DIFFERENCE_FOR_CANCEL')) {
          $itinerary->status = 3;
        } else {
          $itinerary->status = 1;
        }
        $itinerary->update();
      }elseif ($response->TravelItinerary->ItineraryInfo->Ticketing->TicketingStatus == 3) {
        //Estatus 3 es que esta emitido
        $itinerary->status = 2;
        $itinerary->update(); //Actualizamos el estatus a 2 en nuestro sistema
        $airline = (new DecreaseAirlineBalanceTask)->run($response->amount,$response->currency);
        $history = (new CreateAirlineBalanceHistoryTask)->run($response);//restamos el saldo de la aerolinea y creamos un registro del saldo anterior y el nuevo
      }elseif ($response->TravelItinerary->ItineraryInfo->Ticketing->TicketingStatus == 5) {
        //Estatus 5 es q1ue fue cancelado
        $itinerary->status = 3;
        $itinerary->update();//actualizamos el estatus en nuestro sistema
      }elseif (isset($response->Error)) {
        //Si ocurre algun error extra con el localizador se cambia su estatus
        $itinerary->status = 4;
        $itinerary->update();
      }
    return $itinerary;
  }
}
