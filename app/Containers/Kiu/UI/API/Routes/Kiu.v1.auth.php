<?php

// Obtiene la respuesta de la consulta del boton
$router->post('k/GetFlights', [
    'uses' => 'ControllerKiu@GetFlights',
    'middleware' => [
        'auth:api',
    ],
]);
// Ruta para crear el cache por los momentos
$router->post('k/Cache', [
    'uses' => 'ControllerKiu@MakeCache',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('k/GetPrice', [
    'uses' => 'ControllerKiu@GetPrice',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('k/MakeReservation', [
    'uses' => 'ControllerKiu@CreatePnr',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/Booking/Cache', [
    'uses' => 'ControllerKiu@KiuCache',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/Booking/Itin', [
    'uses' => 'ControllerKiu@GetItinerary',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/Policies', [
    'uses' => 'ControllerKiu@getPolicies',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/LocalizableChecker', [
    'uses' => 'ControllerKiu@checkLocalizables',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/AirDemandTicket', [
    'uses' => 'ControllerKiu@demandTicket',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/checkProcess', [
    'uses' => 'ControllerKiu@checkProcess',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('k/checkNotifications', [
    'uses' => 'ControllerKiu@checkProcess',
    'middleware' => [
        'auth:api',
    ],
]);
