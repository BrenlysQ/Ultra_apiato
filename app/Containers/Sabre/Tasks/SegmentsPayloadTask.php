<?php

namespace App\Containers\Sabre\Tasks;


use App\Commons\CommonActions;
use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\Input;
/**
 * Class SegmentsPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class SegmentsPayloadTask extends Task
{
    public function run($payload, $numberinparty)
    {
      $cache = $payload->itinerary;
      $currency = $payload->currency;
      //var_dump($currency); die;
      $count = 1;
      $segsel = '';
      $govcarrier = '';
      $segmentsdata = '';
      for($i=0; $i < $payload->itinstored->trip; $i++){
        $first = $count;
        //$xmlstr .= '<OriginDestinationInformation>';
        $segments = $cache->AirItinerary->OriginDestinationOptions->OriginDestinationOption[$i]->FlightSegment;
        foreach ($segments as $key => $segment) {
          $segmentsdata .= '
          <FlightSegment DepartureDateTime="' . $segment->DepartureDateTime .'"  ArrivalDateTime="' . $segment->ArrivalDateTime . '"'
            . ' FlightNumber="' . $segment->FlightNumber . '" NumberInParty="' . $numberinparty . '" ResBookDesigCode="' . $segment->ResBookDesigCode . '" Status="QF">
            <DestinationLocation LocationCode="' . $segment->ArrivalAirport->LocationCode . '" />
            <MarketingAirline Code="' . $segment->MarketingAirline->Code . '" FlightNumber="' . $segment->FlightNumber . '" />
            <OriginLocation LocationCode="' . $segment->DepartureAirport->LocationCode . '" />
          </FlightSegment>';
          //$segsel .= '<SegmentSelect Number="' . $count . '" RPH="' . $count . '"/>
                      //';
          $govcarrier .= '<GoverningCarrierOverride RPH="' . $count . '">
                            <Airline Code="' . $segment->MarketingAirline->Code . '"/>
                          </GoverningCarrierOverride>';
          $count++;
        }
        $segsel .= '<SegmentSelect EndNumber="' . ($count - 1) . '" Number="' . $first . '" RPH="' . ($i + 1) . '"/>';
        //$xmlstr .= "</OriginDestinationInformation>";
      }
      return $segmentsdata;
    }
}
