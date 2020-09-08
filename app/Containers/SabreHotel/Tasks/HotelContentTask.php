<?php

namespace App\Containers\SabreHotel\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class ValPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class HotelContentTask extends Task
{
    public function run()
    {
      $data = (object) array(
                'PickUpDate' => Input::get('pickupdate','03-10T09:00'),
                'ReturnDate' => Input::get('returndate','03-17T11:00'),
                'LocationCode' => Input::get('locationcode','MAD') 
              );
      $payload = '{
                      "GetHotelContentRQ": {
                          "SearchCriteria": {
                              "HotelRefs": {
                                  "HotelRef": [{
                                      "HotelCode": "1"
                                  }, {
                                      "HotelCode": "1100"
                                  }]
                              },
                              "DescriptiveInfoRef": {
                                  "PropertyInfo": true,
                                  "LocationInfo": true,
                                  "Amenities": true,
                                  "Descriptions": {
                                      "Description": [{
                                          "Type": "Dining"
                                      }, {
                                          "Type": "Alerts"
                                      }]
                                  },
                                  "Airports": true,
                                  "AcceptedCreditCards": true
                              },
                              "ImageRef": {
                                  "MaxImages": "10"
                              }
                          }
                      }
                  }';
      return $payload;
    }
}
