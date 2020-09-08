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
class CarAvailabilitySoapTask extends Task
{
    public function run()
    {
		$data = (object) array(
                'PickUpDate' => Input::get('pickupdate'),
                'ReturnDate' => Input::get('returndate'),
                'LocationCode' => Input::get('locationcode'),
                'Returnlocationcode' => Input::get('returnlocationcode'),
                'se'  => 1
              );
		//print_r($data); die;
      if($data->Returnlocationcode){
        $car_payload = '<OTA_VehAvailRateRQ Version="2.4.1" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                          <VehAvailRQCore QueryType="Shop">
                              <VehRentalCore PickUpDateTime="'.$data->PickUpDate.'" ReturnDateTime="'.$data->ReturnDate.'">
                                  <PickUpLocation LocationCode="'.$data->LocationCode.'" />
                                  <ReturnLocation LocationCode="'.$data->Returnlocationcode.'"/>
                              </VehRentalCore>
                          </VehAvailRQCore>
                      </OTA_VehAvailRateRQ>';
        //print_r($car_payload); die;
      }else{
        $car_payload = '<OTA_VehAvailRateRQ Version="2.4.1" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                          <VehAvailRQCore QueryType="Shop">
                              <VehRentalCore PickUpDateTime="'.$data->PickUpDate.'" ReturnDateTime="'.$data->ReturnDate.'">
                                  <PickUpLocation LocationCode="'.$data->LocationCode.'" />
                              </VehRentalCore>
                          </VehAvailRQCore>

                      </OTA_VehAvailRateRQ>';
        //print_r($car_payload); die;
      }
      return (object) array(
        'xml' => $car_payload,
        'data' => $data
      );
    }
}
