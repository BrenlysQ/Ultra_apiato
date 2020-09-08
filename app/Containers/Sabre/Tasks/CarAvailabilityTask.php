<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class CarAvailabilityTask extends Task
{
    public function run()
    {
      $data = (object) array(
                'PickUpDate' => Input::get('pickupdate','03-10T09:00'),
                'ReturnDate' => Input::get('returndate','03-17T11:00'),
                'LocationCode' => Input::get('locationcode','MAD') 
              );
      $car_payload = '{
            "OTA_VehAvailRateRQ":{
              "VehAvailRQCore":{
                "QueryType":"Shop",
                "VehRentalCore":{
                  "PickUpDateTime":"'.$data->PickUpDate.'",
                  "ReturnDateTime":"'.$data->ReturnDate.'",
                  "PickUpLocation":{
                    "LocationCode":"'.$data->LocationCode.'"
                  }
                }
              }
            }
          }';
      return $car_payload;
    }
}
