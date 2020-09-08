<?php
namespace App\Containers\Kiu\Actions;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use App\Containers\Kiu\Tasks\BookPayloadTask;
use App\Containers\Kiu\Tasks\MakeRequestTask;
use App\Containers\UltraApi\Actions\UltraBook;
use App\Commons\CommonActions;
use App\Commons\TagsGdsHandler;
use App\Containers\Kiu\Actions\KiuHandler;
use App\Mail\BookErrorMail;
class KiuBook{
  public static function Create()
  {
    $cache = (object) KiuHandler::Cache();
    $request = (new BookPayloadTask())->run($cache->flight, $cache->currency);
    //echo $request; die;
    $response = (new MakeRequestTask())->run($request);
	//print_r($response); die;
    //$response = (object) array('BookingReferenceID' => (object) array('ID' => 'XXXXXXX'));
    if(property_exists($response, 'BookingReferenceID')){
        $odo = $cache->flight;
        $odo->outbound = $odo->outbound;
        $odo->return = $odo->return;
        $itinerary = json_decode((TagsGdsHandler::GetGdsTag())->itinerary);
        $data = CommonActions::CreateObject();
        $data->itineraryid = $response->BookingReferenceID->ID;
        $data->odo = $odo;
        $data->itinerary = $itinerary;
	    $data->freelance = Input::get('freelance_id',0);
      return UltraBook::Book($data);
    }else{
        $itinerary = json_decode((TagsGdsHandler::GetGdsTag())->itinerary);
        CommonActions::makeBookErrorLog($itinerary,$response);
        Mail::to('plusultradesarrollo@gmail.com')->send(new BookErrorMail($itinerary));
        return (object) array(
            'error' => true,
            'message' => 'ItineraryRef unavailable.'
        );
    }
  }
  public static function DemoCache()
  {
    return '{
    "flights": [
        {
            "outbound": [
                {
                    "stops": [
                        {
                            "airport": {
                                "IATA": "AUA",
                                "name": "Reina Beatrix Intl"
                            },
                            "waittime": 280
                        }
                    ],
                    "havestops": true,
                    "segments": [
                        {
                            "origin": {
                                "id": 2868,
                                "name": "Arturo Michelena Intl",
                                "city": "Valencia",
                                "country": "Venezuela",
                                "IATA": "VLN",
                                "ICAO": "SVVA",
                                "latitude": "10.149733",
                                "longitude": "-67.9284",
                                "altitude": "1417",
                                "timezone": "-4.5",
                                "DST": "U",
                                "tz": "America/Caracas"
                            },
                            "destination": {
                                "id": 2895,
                                "name": "Reina Beatrix Intl",
                                "city": "Oranjestad",
                                "country": "Aruba",
                                "IATA": "AUA",
                                "ICAO": "TNCA",
                                "latitude": "12.501389",
                                "longitude": "-70.015221",
                                "altitude": "60",
                                "timezone": "-4",
                                "DST": "U",
                                "tz": "America/Aruba"
                            },
                            "availclasses": [
                                "K"
                            ],
                            "airline": {
                                "id": 216,
                                "name": "Aruba Airlines",
                                "alias": "",
                                "iata": "AG",
                                "icao": "ABR",
                                "callsign": "CONTRACT",
                                "country": "Ireland",
                                "active": "N"
                            },
                            "dtime": "2017-10-13 11:45:00",
                            "atime": "2017-10-13 12:50:00",
                            "flightno": "112",
                            "elapsedtime": 65
                        },
                        {
                            "origin": {
                                "id": 2895,
                                "name": "Reina Beatrix Intl",
                                "city": "Oranjestad",
                                "country": "Aruba",
                                "IATA": "AUA",
                                "ICAO": "TNCA",
                                "latitude": "12.501389",
                                "longitude": "-70.015221",
                                "altitude": "60",
                                "timezone": "-4",
                                "DST": "U",
                                "tz": "America/Aruba"
                            },
                            "destination": {
                                "id": 3576,
                                "name": "Miami Intl",
                                "city": "Miami",
                                "country": "United States",
                                "IATA": "MIA",
                                "ICAO": "KMIA",
                                "latitude": "25.79325",
                                "longitude": "-80.290556",
                                "altitude": "8",
                                "timezone": "-5",
                                "DST": "A",
                                "tz": "America/New_York"
                            },
                            "availclasses": [
                                "K"
                            ],
                            "airline": {
                                "id": 216,
                                "name": "Aruba Airlines",
                                "alias": "",
                                "iata": "AG",
                                "icao": "ABR",
                                "callsign": "CONTRACT",
                                "country": "Ireland",
                                "active": "N"
                            },
                            "dtime": "2017-10-13 17:30:00",
                            "atime": "2017-10-13 20:30:00",
                            "flightno": "821",
                            "elapsedtime": 240
                        }
                    ],
                    "origin": {
                        "IATA": "VLN",
                        "name": "Arturo Michelena Intl"
                    },
                    "destination": {
                        "IATA": "MIA",
                        "name": "Miami Intl"
                    },
                    "dtime": "2017-10-13 11:45:00",
                    "atime": "2017-10-13 20:30:00",
                    "airlines": [
                        "AG"
                    ],
                    "segment_classes": [
                        [
                            "K",
                            "K"
                        ]
                    ],
                    "elapsedtime": 305,
                    "governor_carrier": "AG",
                    "seqnumber": 1,
                    "classes": [
                        "K",
                        "K"
                    ]
                }
            ],
            "return": [
                {
                    "stops": [
                        {
                            "airport": {
                                "IATA": "AUA",
                                "name": "Reina Beatrix Intl"
                            },
                            "waittime": 170
                        }
                    ],
                    "havestops": true,
                    "segments": [
                        {
                            "origin": {
                                "id": 3576,
                                "name": "Miami Intl",
                                "city": "Miami",
                                "country": "United States",
                                "IATA": "MIA",
                                "ICAO": "KMIA",
                                "latitude": "25.79325",
                                "longitude": "-80.290556",
                                "altitude": "8",
                                "timezone": "-5",
                                "DST": "A",
                                "tz": "America/New_York"
                            },
                            "destination": {
                                "id": 2895,
                                "name": "Reina Beatrix Intl",
                                "city": "Oranjestad",
                                "country": "Aruba",
                                "IATA": "AUA",
                                "ICAO": "TNCA",
                                "latitude": "12.501389",
                                "longitude": "-70.015221",
                                "altitude": "60",
                                "timezone": "-4",
                                "DST": "U",
                                "tz": "America/Aruba"
                            },
                            "availclasses": [
                                "K"
                            ],
                            "airline": {
                                "id": 216,
                                "name": "Aruba Airlines",
                                "alias": "",
                                "iata": "AG",
                                "icao": "ABR",
                                "callsign": "CONTRACT",
                                "country": "Ireland",
                                "active": "N"
                            },
                            "dtime": "2017-10-16 08:00:00",
                            "atime": "2017-10-16 11:00:00",
                            "flightno": "820",
                            "elapsedtime": 120
                        },
                        {
                            "origin": {
                                "id": 2895,
                                "name": "Reina Beatrix Intl",
                                "city": "Oranjestad",
                                "country": "Aruba",
                                "IATA": "AUA",
                                "ICAO": "TNCA",
                                "latitude": "12.501389",
                                "longitude": "-70.015221",
                                "altitude": "60",
                                "timezone": "-4",
                                "DST": "U",
                                "tz": "America/Aruba"
                            },
                            "destination": {
                                "id": 2868,
                                "name": "Arturo Michelena Intl",
                                "city": "Valencia",
                                "country": "Venezuela",
                                "IATA": "VLN",
                                "ICAO": "SVVA",
                                "latitude": "10.149733",
                                "longitude": "-67.9284",
                                "altitude": "1417",
                                "timezone": "-4.5",
                                "DST": "U",
                                "tz": "America/Caracas"
                            },
                            "availclasses": [
                                "K"
                            ],
                            "airline": {
                                "id": 216,
                                "name": "Aruba Airlines",
                                "alias": "",
                                "iata": "AG",
                                "icao": "ABR",
                                "callsign": "CONTRACT",
                                "country": "Ireland",
                                "active": "N"
                            },
                            "dtime": "2017-10-16 13:50:00",
                            "atime": "2017-10-16 14:55:00",
                            "flightno": "111",
                            "elapsedtime": 65
                        }
                    ],
                    "origin": {
                        "IATA": "MIA",
                        "name": "Miami Intl"
                    },
                    "destination": {
                        "IATA": "VLN",
                        "name": "Arturo Michelena Intl"
                    },
                    "dtime": "2017-10-16 08:00:00",
                    "atime": "2017-10-16 14:55:00",
                    "airlines": [
                        "AG"
                    ],
                    "segment_classes": [
                        [
                            "K",
                            "K"
                        ]
                    ],
                    "elapsedtime": 185,
                    "governor_carrier": "AG",
                    "seqnumber": 1,
                    "classes": [
                        "Y",
                        "B"
                    ]
                }
            ],
            "price": {
                "TotalFare": {
                    "Amount": "3199209.48",
                    "CurrencyCode": "VEF"
                },
                "Taxes": {
                    "Tax": [
                        {
                            "TaxCode": "AJ",
                            "Amount": "900",
                            "CurrencyCode": "VEF"
                        },
                        {
                            "TaxCode": "AK",
                            "Amount": "3000",
                            "CurrencyCode": "VEF"
                        },
                        {
                            "TaxCode": "EU",
                            "Amount": "28063.64",
                            "CurrencyCode": "VEF"
                        },
                        {
                            "TaxCode": "VJ",
                            "Amount": "38500",
                            "CurrencyCode": "VEF"
                        },
                        {
                            "TaxCode": "YN",
                            "Amount": "168381.84",
                            "CurrencyCode": "VEF"
                        },
                        {
                            "TaxCode": "YQ",
                            "Amount": "154000",
                            "CurrencyCode": "VEF"
                        }
                    ]
                },
                "BaseFare": {
                    "Amount": "2806364",
                    "CurrencyCode": "VEF"
                },
                "GlobalFare": {
                    "FeeAmount": 0,
                    "BaseInter": 2806364,
                    "TotalTax": 392845.48,
                    "BaseAmount": 3199209.48,
                    "TotalAmount": 3199209.48,
                    "CurrencyCode": "Bsf."
                }
            },
            "footprint": "4e8640ef097564ac9abcbaf5d030a912",
            "seqnumber": 1
        }
    ],
    "tagpu": "U1CP93KPPg"
}';
  }
}
?>
