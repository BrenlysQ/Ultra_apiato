<?php

namespace App\Containers\Insurance\Tasks;

use App\Ship\Parents\Tasks\Task;
use App\Commons\CommonActions;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Input;

class EmitsCotTask extends Task
{
    public function run($invoice)
    {
      $paxesdetails = json_decode(Input::get('datapaxes'));
      $paxes = '';
      $insu_data = json_decode(Input::get('insurance_data'));
      foreach($paxesdetails as $index => $pax){
    $ageid = CommonActions::getPaxAgeId($pax->dob);
        if($index == 0){
          $billing = '
            "firstname": "' . $pax->firstname . '",
            "lastname": "' . $pax->lastname . '",
            "phone": "' . $pax->phone . '",
            "email": "' . $pax->email . '"';
          // $emergency = '
          // "firstname": "' . $pax->firstname . '",
          // "lastname": "' . $pax->lastname . '",
          // "phone": "' . $pax->phone . '"';
        }
        $paxes .= '
              {
                "ageid": "' . $ageid . '",
                "firstname": "' . $pax->firstname . '",
                "lastname": "' . $pax->lastname . '",
                "document": "' . $pax->passport . '",
                "borndate": "' . $pax->dob . '"
              },';
      }
      $data = array('body'=>'
      {
        "item": {
          "billing": {
              ' . $billing . '
          },
          "passengers": ['
              . substr($paxes, 0, -1) . '
          ],
          "emergency": {
              ' . $billing .'
          },
          "quotation": "' . $insu_data->id_cot . '",
          "currency": "USD",
          "invoicenumber" : ' . $invoice->id . ',
          "plan": ' . $insu_data->id_plan . '
        }
      }');
      //$data = array('body'=>'[]');
      return $data;
    }

}
