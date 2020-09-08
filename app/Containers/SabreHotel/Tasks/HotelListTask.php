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
class HotelListTask extends Task
{
    public function run()
    {
      $data = (object) array(
                'PickUpDate' => Input::get('pickupdate','03-10T09:00'),
                'ReturnDate' => Input::get('returndate','03-17T11:00'),
                'LocationCode' => Input::get('locationcode','MAD') 
              );
      $payload = '<GetHotelContentRQ xmlns="http://services.sabre.com/hotel/content/v1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" version="1.0.0" xsi:schemaLocation="http://services.sabre.com/hotel/content/v1 GetHotelContentRQ.xsd">
                      <SearchCriteria>
                          <HotelRefs>
                              <HotelRef HotelCode="1" />
                              <HotelRef HotelCode="1100" />
                          </HotelRefs>
                          <DescriptiveInfoRef>
                              <PropertyInfo>true</PropertyInfo>
                              <LocationInfo>true</LocationInfo>
                              <Amenities>true</Amenities>
                              <Descriptions>
                                  <Description Type="Dining" />
                                  <Description Type="Alerts" />
                              </Descriptions>
                              <Airports>true</Airports>
                              <AcceptedCreditCards>true</AcceptedCreditCards>
                          </DescriptiveInfoRef>
                          <ImageRef MaxImages="10">
                          </ImageRef>
                      </SearchCriteria>
                  </GetHotelContentRQ>';
      return $payload;
    }
}
