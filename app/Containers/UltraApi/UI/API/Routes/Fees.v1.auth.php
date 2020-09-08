<?php
$router->post('fee/store', [
    'uses' => 'ControllerFees@StoreFee',
    'middleware' => [
        'auth:api',
    ],
]);
$router->get('fee/list/', [
    'uses' => 'ControllerFees@FeeList',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('fee/get', [
    'uses' => 'ControllerFees@GetFee',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('fee/delete', [
    'uses' => 'ControllerFees@DeleteFee',
    'middleware' => [
        'auth:api',
    ],
]);

/*
$router->post('currencies/assign', [
    'uses' => 'ControllerCurrencies@AssignCurrency',
    'middleware' => [
        'api.auth',
    ],
]);
$router->post('currencies/get', [
    'uses' => 'ControllerCurrencies@GetCurrency',
    'middleware' => [
        'api.auth',
    ],
]);
$router->post('currencies/delete', [
    'uses' => 'ControllerCurrencies@DeleteCurrency',
    'middleware' => [
        'api.auth',
    ],
]);
*/
