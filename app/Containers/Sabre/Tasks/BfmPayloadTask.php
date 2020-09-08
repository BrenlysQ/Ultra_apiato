<?php

namespace App\Containers\Sabre\Tasks;

use App\Ship\Parents\Tasks\Task;
use Illuminate\Support\Facades\App;
use App\Containers\UltraApi\Actions\Currencies\CurrenciesHandler;
use Illuminate\Support\Facades\Input;
use App\Containers\Sabre\Commons\SabreCommons;
/**
 * Class BookPayloadTask.
 *
 * @author Churromorales <churrmorales20@gmail.com>
 * @owner Plus Ultra CA
 */
class BfmPayloadTask extends Task
{
    public function run($data)
    {
      $currency = CurrenciesHandler::GetbyId($data->currency);
      //$currency = $currencies[];
      $paxes = SabreCommons::RequestPayloadPax($data);
      $triptype = 'OneWay';
      $totalpaxes = $data->adult_count + $data->child_count + $data->inf_count;
      $request = '
      {
          "OTA_AirLowFareSearchRQ": {
            "AvailableFlightsOnly" : true,
            "Version" : "3.1.0",
            "ResponseType" : "OTA",
            "ResponseVersion" : "3.1.0",
              "POS": {
                  "Source": [
                      {
                          "PseudoCityCode": "' . $currency->pcc . '",
                          "RequestorID": {
                              "Type": "1",
                              "ID": "1",
                              "CompanyName": {}
                          }
                      }
                  ]
              },
              "OriginDestinationInformation": [
                  {
                      "RPH": "1",
                      "DepartureDateTime": "' . $data->departure_date . 'T11:00:00",
                      "OriginLocation": {
                          "LocationCode": "' . $data->departure_city . '"
                      },
                      "DestinationLocation": {
                          "LocationCode": "' . $data->destination_city . '"
                      },
                      "TPA_Extensions": {
                          "SegmentType": {
                              "Code": "O"
                          }
                      }
                  }';
              if(SabreCommons::isRoundTrip($data->trip)){
                $triptype = 'Return';
                $request .= ',
                {
                    "RPH": "2",
                    "DepartureDateTime": "' . $data->return_date . 'T11:00:00",
                    "OriginLocation": {
                        "LocationCode": "' . $data->destination_city . '"
                    },
                    "DestinationLocation": {
                        "LocationCode": "' . $data->departure_city . '"
                    }
                }';
              }
              $request .= '
              ],
          "TravelPreferences": {
              "ValidInterlineTicket": true,
              "CabinPref": [
                  {
                      "Cabin": "' .  $data->cabin . '",
                      "PreferLevel": "Preferred"
                  }
              ],';
      if($data->currency == 3){
        $request .= '
                      "VendorPref": [
                        {
                          "Code": "9V",
                          "PreferLevel": "Only"
                        },
                        {
                          "Code": "R7",
                          "PreferLevel": "Only"
                        },
                        {
                          "Code": "S3",
                          "PreferLevel": "Only"
                        }
                      ],';
      }
      $request .= '
                  "TPA_Extensions": {
                      "TripType": {
                          "Value": "' . $triptype . '"
                      },
                      "ExcludeCallDirectCarriers": {
                          "Enabled": true
                      }
                  }
              },
              "TravelerInfoSummary": {
                  "SeatsRequested": [
                      ' . $totalpaxes . '
                  ],
                  "AirTravelerAvail": [
                      {
                          "PassengerTypeQuantity": [
                            ' . $paxes . '
                          ]
                      }
                  ],
                  "PriceRequestInformation" : {
                    "CurrencyCode" : "' . $currency->code . '"
                  }
              },
              "TPA_Extensions": {
                "IntelliSellTransaction": {
                    "RequestType": {
                        "Name": "50ITINS"
                    },
                    "DisableCache": false
                }
              }
          }
      }';
	  //echo $request; die;
      return $request;
    }
    private static function RequestPaxes($count,$type){
      if($count > 0){
        return '{
                    "Code": "' . $type . '",
                    "Quantity": ' . $count . '
                },';
      }
    }
}
