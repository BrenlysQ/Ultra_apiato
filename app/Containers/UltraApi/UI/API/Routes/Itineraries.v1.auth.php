<?php
$router->post('itineraries/show', [
    'uses' => 'ControllerItineraries@UserItineraries',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('itineraries/test', [
    'uses' => 'ControllerItineraries@test',
    'middleware' => [
        'auth:api',
    ],
]);
