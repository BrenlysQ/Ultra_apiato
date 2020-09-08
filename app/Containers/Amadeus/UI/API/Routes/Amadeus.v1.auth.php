<?php

// Default root route
$router->post('am/GetFlights', [
    'uses' => 'ControllerAmadeus@GetFlights',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/Booking/Cache', [
    'uses' => 'ControllerAmadeus@AmadeusCache',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/MakeReservation', [
    'uses' => 'ControllerAmadeus@CreatePnr',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/PnrRetrieve', [
    'uses' => 'ControllerAmadeus@PnrRetrieve',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/getCalendar', [
    'uses' => 'ControllerAmadeus@getCalendar',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/ticketIssue', [
    'uses' => 'ControllerAmadeus@issueTicket',
    'middleware' => [
        'auth:api',
    ],
]);



/*$router->post('am/BookFlights', [
    'uses' => 'ControllerAmadeus@BookFlights',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/CreatePNR', [
    'uses' => 'ControllerAmadeus@CreatePNR',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/PricePNR', [
    'uses' => 'ControllerAmadeus@PricePNR',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/StoreFare', [
    'uses' => 'ControllerAmadeus@StoreFare',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/CreateFOP', [
    'uses' => 'ControllerAmadeus@CreateFOP',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('am/SavePNR', [
    'uses' => 'ControllerAmadeus@SavePNR',
    'middleware' => [
        'auth:api',
    ],
]);*/
