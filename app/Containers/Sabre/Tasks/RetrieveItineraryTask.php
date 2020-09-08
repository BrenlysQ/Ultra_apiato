<?php

namespace App\Containers\Sabre\Tasks;

use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class RetrieveItineraryTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class RetrieveItineraryTask extends Task
{ 
    public function run($data)
    {
        $request = '<GetReservationRQ Version="1.18.0" xmlns="http://webservices.sabre.com/pnrbuilder/v1_18">
                        <Locator>'.$data->itinerary_id.'</Locator>
                        <RequestType>Stateful</RequestType>
                        <ReturnOptions>
                            <ViewName>VaDefaultWithPq</ViewName>
                            <ResponseFormat>STL</ResponseFormat>
                        </ReturnOptions>
                    </GetReservationRQ>';
        return $request;
    }
}
