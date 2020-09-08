<?php

// Default root route
$router->post('s/GetFlights', [
    'uses' => 'ControllerSabre@GetBFM',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('s/MakeReservation', [
    'uses' => 'ControllerSabre@CreatePnr',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/Booking/Cache', [
    'uses' => 'ControllerSabre@CacheBFM',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/Booking/Itin', [
    'uses' => 'ControllerSabre@GetItinerary',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/Policies', [
    'uses' => 'ControllerSabre@getPolicies',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/AlternateDays', [
    'uses' => 'ControllerSabre@getAlternateDays',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/GetCars', [
    'uses' => 'ControllerSabre@getCarAvailability',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/GetCarsSoap', [
    'uses' => 'ControllerSabre@getCarAvailabilitySoap',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/GetCarsLocationByAirport', [
    'uses' => 'ControllerSabre@getCarLocationByAirport',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/GetCarsLocation', [
    'uses' => 'ControllerSabre@getCarLocation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/GetCarsDetailsLocation', [
    'uses' => 'ControllerSabre@getCarDetailsLocation',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/BookCar', [
    'uses' => 'ControllerSabre@getBookCar',
    'middleware' => [
        'auth:api',
    ],
]);