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
class CarDetailsLocationTask extends Task
{
    public function run($company)
    {
      $data = (object) array(
                'LocationCode' => Input::get('locationcode','MIA'),
                'Company' => $company
              );
      //dd($avail);
      $car_payload = '<OTA_VehLocDetailRQ xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" Version="2.1.0">
                        <VehAvailRQCore>
                          <VehRentalCore>
                            <PickUpLocation LocationCode="' . $data->LocationCode . '"/>
                          </VehRentalCore>
                          <VendorPrefs>
                            <VendorPref Code="' . $data->Company . '"/>
                          </VendorPrefs>
                        </VehAvailRQCore>
                      </OTA_VehLocDetailRQ>';
      //print_r($car_payload); die;
      return $car_payload;
    }
}
