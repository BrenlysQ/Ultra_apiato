<?php
$router->post('user/fee/store', [
    'uses' => 'ControllerUsers@SaveUserFee',
    'middleware' => [
        'auth:api',
    ],
]);
$router->get('user/fee/list', [
    'uses' => 'ControllerUsers@GetFeeList',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('user/fee/get', [
    'uses' => 'ControllerUsers@GetFee',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('user/bank/assign', [
    'uses' => 'ControllerUsers@BankAssign',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('user/bank/list', [
    'uses' => 'ControllerUsers@GetBanksList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('/satellite_token', [
    'uses' => 'ControllerUsers@GetSatelliteToken',
    'middleware' => [
        'auth:api',
    ],
]);