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
class CarLocationByAirportTask extends Task
{
    public function run()
    {
      $data = (object) array(
                'LocationCode' => Input::get('locationcode','HNL') 
              );
      $car_payload = '<VehLocationListRQ Version="2.0.0" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"> 
                        <VehAvailRQCore> 
                          <VehRentalCore> 
                            <PickUpLocation LocationCode="'.$data->LocationCode.'"/> 
                          </VehRentalCore> 
                        </VehAvailRQCore> 
                      </VehLocationListRQ>';
      return $car_payload;
    }
}
