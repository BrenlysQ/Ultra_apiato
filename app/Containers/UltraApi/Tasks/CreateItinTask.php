<?php

namespace App\Containers\UltraApi\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\Input;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\UltraApi\Actions\Invoices\InvoicesHandler;
use App\Containers\Invoice\Actions\InvoiceHandler;
use App\Containers\Invoice\Models\ItemsModel;
use App\Containers\Sabre\Actions\Airports;
/**
 * Class UpdateUserTask.
 *
 * @author Plus Ultra
 */
class CreateItinTask extends Task
{

    /**
     * @param $userData
     * @param $userId
     *
     * @return  mixed
     * @throws \App\Ship\Exceptions\UpdateResourceFailedException
     */
    public function run($data)
    {
      //pasar a task por ejemplo CreateItinTask
        $usersatdata = Input::get('usersat');
    $data->odo->airport_destination = new Airports($data->itinerary->destination_city);
        $usersatid = (json_decode($usersatdata))->id;
        $itinerarydata = json_encode($data->itinerary);
    $paxesdetails = json_decode(Input::get('datapaxes'));
    $paxes = CreateItinTask::encodePaxes($paxesdetails);
    if($data->itinerary->se == 13){
      foreach($paxesdetails as $key => $pax){
        if(count($paxes) == 1){
          $pax->footprint = $paxes[0];
        }else{
          $pax->footprint = $paxes[$key];
        }
      }
    }
    if ($data->freelance != 0){
      $satelite = $data->freelance;
    }else{
      $satelite = Auth::id();
    }
    if($data->itinerary->trip == 2){
      $ret_date = $data->itinerary->return_date;
    }else{
      $ret_date = $data->itinerary->departure_date;
    }
        $itinerary = new ItinModel();
        $itinerary->itinerary_id = $data->itineraryid;
        $itinerary->origin = $data->itinerary->departure_city;
        $itinerary->destination = $data->itinerary->destination_city;
        $itinerary->date_return = $ret_date;
        $itinerary->date_departure = $data->itinerary->departure_date;
        $itinerary->odo = json_encode($data->odo);
        $itinerary->paxes = json_encode($paxesdetails);
        $itinerary->itinerary = $itinerarydata;
        $itinerary->usersatdata = $usersatdata;
        $itinerary->usersatid = $usersatid;
        $itinerary->satelite = $satelite;
        $itinerary->save();
        //AL CREAR UN INVOICEABLE LA RESPUESTA DEL TASK DEBE SER
        //UN OBJECT CON 2 PROPIEDADES obj y data obj=Invoiceable data = data del invoiceable
        $ret = CommonActions::CreateObject();
        $ret->obj = $itinerary; //INVOICEABLE
        $ret->data = (object) array( // DATA REQUERIDA DEL INVOICEABLE
          'fee' => $data->odo->price->GlobalFare->FeeAmount,
      'feepu' => $data->odo->price->GlobalFare->BaseAmount - $data->odo->price->GlobalFare->BaseInter - $data->odo->price->GlobalFare->TotalTax,
          'total_amount' => $data->odo->price->GlobalFare->TotalAmount,
          'total_base' => $data->odo->price->GlobalFare->Base2Show,
          'total_tax' => $data->odo->price->GlobalFare->TotalTax,
          'usersatid' => $usersatid,
          'usersatdata' => $usersatdata,
          'currency' => $data->itinerary->currency,
		  'satelite' => $satelite,
		  'seller' => $data->seller
        );
        return $ret;
    }
  private static function encodePaxes($paxesdetails){
    $paxes = array();
    foreach($paxesdetails as $pax){
      $paxes[] = md5(
        strtolower($pax->firstname).
        strtolower($pax->lastname).
        $pax->type
      );
    }
    return $paxes;
  }
}
