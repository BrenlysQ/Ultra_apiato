<?php
$router->post('satellite/store', [
    'uses' => 'ControllerSatellites@StoreSatellite',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('satellite/getsecret', [
    'uses' => 'ControllerSatellites@GetSecret',
    'middleware' => [
        'auth:api',
    ],
]);
$router->get('satellite/list', [
    'uses' => 'ControllerSatellites@GetSatelliteList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('satellite/deletedlist', [
    'uses' => 'ControllerSatellites@GetDeletedSatelliteList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('satellite/delete', [
    'uses' => 'ControllerSatellites@DeleteSatellite',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('satellite/edit', [
    'uses' => 'ControllerSatellites@bundledSatellite',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('satellite/update', [
    'uses' => 'ControllerSatellites@UpdateSatellite',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('satellite/update/password', [
    'uses' => 'ControllerSatellites@UpdateSatellitePassword',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('satellite/getSatelitesBalance', [
    'uses' => 'ControllerSatellites@getSatelitesBalance',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('satellite/getSatelliteInvoices', [
    'uses' => 'ControllerSatellites@getSateliteInvoices',
    'middleware' => [
        'auth:api',
    ],
]);
