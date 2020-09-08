<?php

namespace App\Containers\Sabre\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class CarLocationTask extends Task
{
    public function run()
    {
      $data = (object) array(
                'CityName' => Input::get('CityName','MIAMI'),
                'StateCode' => Input::get('StateCode','FL'),
                //'StreetNmbr' => Input::get('StreetNmbr','3150 SABRE DRIVE'),
                'PickUpDate' => Input::get('pickupdate','03-22T09:00'),
                'ReturnDate' => Input::get('returndate','03-29T11:00')
              );
      $car_payload = '<VehLocationFinderRQ Version="2.3.0" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                        <VehAvailRQCore>
                            <LocationDetails>
                                <Address>
                                    <CityName>'.$data->CityName.'</CityName>
                                    <StateCountyProv StateCode="'.$data->StateCode.'" />
                                </Address>
                            </LocationDetails>
                            <VehRentalCore PickUpDateTime="'.$data->PickUpDate.'" ReturnDateTime="'.$data->ReturnDate.'" />
                        </VehAvailRQCore>
                    </VehLocationFinderRQ>';
      return $car_payload;
    }
}