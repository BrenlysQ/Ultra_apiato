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
class HotelAvailabilityTask extends Task
{
    public function run()
    {
      $data = (object) array(
                'PickUpDate' => Input::get('pickupdate','03-10T09:00'),
                'ReturnDate' => Input::get('returndate','03-17T11:00'),
                'LocationCode' => Input::get('locationcode','MAD') 
              );
      $payload = '<OTA_HotelAvailRQ Version="2.3.0" xmlns="http://webservices.sabre.com/sabreXML/2011/10" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
                          <AvailRequestSegment>
                              <Customer>
                                  <Corporate>
                                      <ID>ABC123</ID>
                                  </Corporate>
                              </Customer>
                              <GuestCounts Count="2" />
                              <HotelSearchCriteria>
                                  <Criterion>
                                      <HotelRef HotelCityCode="DFW" />
                                  </Criterion>
                              </HotelSearchCriteria>
                              <TimeSpan End="04-24" Start="04-22" />
                          </AvailRequestSegment>
                      </OTA_HotelAvailRQ>';
      return $payload;
    }
}
