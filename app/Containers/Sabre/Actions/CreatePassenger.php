<?php
namespace App\Containers\Sabre\Actions;
use App\Ship\Parents\Actions\Action;
use Illuminate\Support\Facades\Auth;
use Mail;
use App\Containers\Payments\Actions\PaymentsHandler;
use App\Mail\ItineraryStored;
use Illuminate\Support\Facades\Input;
use App\Containers\Payments\Actions\PgatewayHandler;
use App\Containers\UltraApi\Actions\Itineraries\Models\ItinModel;
use App\Containers\UltraApi\Actions\Invoices\Models\InvoiceModel;
use App\Containers\UltraApi\Actions\UltraBook;
class CreatePassenger extends Action{
  private $restclient;
  private $auth;
  private $token;
  public function __construct()
  {
    /*$this->auth = new SabreAuth();
    $this->token = $this->auth->ValidateToken(true);
    $this->restclient = new RestClient($this->token);*/
  }
  public function CreateSoap()
  {
    set_time_limit(0);
    $url = '/v1.0.0/passenger/records?mode=create';
    $response = CreatePassengerSoap::CreatePassenger();
    //var_dump($response); die;
    if(!property_exists($response,'error')){
		$response->odo = json_decode(json_encode($response->odo));
		$response->odo->outbound = $response->odo->outboundflight;
		$response->odo->return = $response->odo->returnflight;
      return UltraBook::Book($response);
    }
    return $response;
  }
  public function SayHey()
  {
    return 'Hello WEY';
  }
  private function PreparePayloadRest($cache)
  {
    $cache = json_decode($cache);
    //print_r($cache); die;
      $request = '{
    "CreatePassengerNameRecordRQ": {
        "targetCity": "' . getenv('SABRE_GROUP') . '",
        "Profile": {
            "UniqueID": {
                "ID": ""
            }
        },
        "AirBook": {
            "OriginDestinationInformation": {
                "FlightSegment": [
                  ';
              for($i=0; $i<1; $i++){
                $segments = $cache->AirItinerary->OriginDestinationOptions->OriginDestinationOption[$i]->FlightSegment;
                foreach ($segments as $segment) {
                  $request .= '
                      {
                        "ArrivalDateTime": "' . $segment->ArrivalDateTime . '",
                        "DepartureDateTime": "' . $segment->DepartureDateTime . '",
                        "FlightNumber": "' . $segment->FlightNumber . '",
                        "NumberInParty": "1",
                        "ResBookDesigCode": "' . $segment->ResBookDesigCode . '",
                        "Status": "NN",
                        "DestinationLocation": {
                            "LocationCode": "' . $segment->ArrivalAirport->LocationCode . '"
                        },
                        "MarketingAirline": {
                            "Code": "' . $segment->OperatingAirline->Code . '",
                            "FlightNumber": "' . $segment->FlightNumber . '"
                        },
                        "MarriageGrp": "' . $segment->MarriageGrp . '",
                        "OriginLocation": {
                            "LocationCode": "' . $segment->DepartureAirport->LocationCode . '"
                        }
                    },';
                }
              }
        $request = substr($request, 0, -1);
        $request .= '
              ]
            }
        },
        "SpecialReqDetails": {
            "AirSeat": {
                "Seats": {
                    "Seat": [{
                        "NameNumber": "1.1",
                        "Preference": "AN",
                        "SegmentNumber": "1"
                    },
                    {
                        "NameNumber": "2.1",
                        "Preference": "AN",
                        "SegmentNumber": "1"
                    },
                    {
                        "NameNumber": "3.1",
                        "Preference": "AN",
                        "SegmentNumber": "1"
                    }]
                }
            },
            "SpecialService": {
                "SpecialServiceInfo": {
                    "AdvancePassenger" :[
                      {
                        "Document" : {
                          "ExpirationDate" : "2018-05-26",
                          "Number" : "1234567890",
                          "Type" : "P",
                          "IssueCountry" : "FR",
                          "NationalityCountry" : "FR"
                        },
                        "PersonName" : {
                          "DateOfBirth" : "1980-12-02",
                          "Gender" : "M",
                          "NameNumber" : "1.1",
                          "DocumentHolder" : true,
                          "GivenName" : "Hello APi",
                          "MiddleName" : "Hello APi",
                          "Surname" : "Hello APi"
                        }
                      }
                    ]
                }
            }
        },
        "PostProcessing": {
            "RedisplayReservation": true,
            "ARUNK": "",
            "QueuePlace": {
                "QueueInfo": {
                    "QueueIdentifier": [{
                        "Number": "100",
                        "PrefatoryInstructionCode": "11"
                    }]
                }
            },
            "EndTransaction": {
                "Source": {
                    "ReceivedFrom": "SWS TEST"
                }
            }
        },
        "AirPrice": {
            "PriceRequestInformation": {
                "OptionalQualifiers": {
                    "MiscQualifiers": {
                        "TourCode": {
                            "Text": "TEST1212"
                        }
                    },
                    "PricingQualifiers": {
                        "PassengerType": [{
                            "Code": "ADT",
                            "Quantity": "2"
                        }]
                    }
                }
            }
        }
    }
}';
      return $request;
  }
}
?>
