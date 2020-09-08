<?php
$router->post('currencies/store', [
    'uses' => 'ControllerCurrencies@StoreCurr',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('currencies/assign', [
    'uses' => 'ControllerCurrencies@AssignCurrency',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('currencies/get', [
    'uses' => 'ControllerCurrencies@GetCurrency',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('currencies/delete', [
    'uses' => 'ControllerCurrencies@DeleteCurrency',
    'middleware' => [
        'auth:api',
    ],
]);
$router->get('currencies/list/', [
    'uses' => 'ControllerCurrencies@ListCurrencies',
    'middleware' => [
        'auth:api',
    ],
]);
