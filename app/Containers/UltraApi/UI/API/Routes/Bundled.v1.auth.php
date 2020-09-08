<?php

$router->get('bundled/feeform', [
    'uses' => 'ControllerBundled@FeeForm',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('bundled/feeform', [
    'uses' => 'ControllerBundled@FeeForm',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('bundled/booking', [
    'uses' => 'ControllerBundled@BookingView',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('bundled/booking2', [
    'uses' => 'ControllerBundled@BookingView2',
    'middleware' => [
        'auth:api',
    ],
]);


$router->post('routes/store', [
    'uses' => 'ControllerBundled@RouteStore',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('routes/list', [
    'uses' => 'ControllerBundled@ListRoutes',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('routes/get', [
    'uses' => 'ControllerBundled@GetRoute',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('routes/delete', [
    'uses' => 'ControllerBundled@DeleteRoute',
    'middleware' => [
        'auth:api',
    ],
]);
