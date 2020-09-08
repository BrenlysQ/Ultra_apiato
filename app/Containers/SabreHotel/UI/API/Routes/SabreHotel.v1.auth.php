<?php
$router->post('s/HotelAvailability', [
    'uses' => 'ControllerSabre@getHotelAvailability',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/HotelContent', [
    'uses' => 'ControllerSabre@getHotelContent',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('s/HotelList', [
    'uses' => 'ControllerSabre@getHotelList',
    'middleware' => [
        'auth:api',
    ],
]);