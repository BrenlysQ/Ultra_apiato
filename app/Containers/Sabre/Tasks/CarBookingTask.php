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
class CarBookingTask extends Task
{
    public function run()
    {
      $car_payload = '<OTA_VehResRQ Version="2.1.0" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                        <VehResRQCore>
                            <VehPrefs>
                                <VehPref>
                                    <VehType>SCAR</VehType>
                                </VehPref>
                            </VehPrefs>
                            <VehRentalCore PickUpDateTime="12-21T10:00" Quantity="1" ReturnDateTime="12-24T12:00">
                                <PickUpLocation ExtendedLocationCode="LONC18" LocationCode="DFW" />
                            </VehRentalCore>
                            <VendorPrefs>
                                <VendorPref Code="ZE" />
                            </VendorPrefs>
                        </VehResRQCore>
                    </OTA_VehResRQ>';
      return $car_payload;
    }
}
