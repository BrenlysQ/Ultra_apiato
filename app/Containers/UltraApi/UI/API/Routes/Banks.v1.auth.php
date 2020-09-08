<?php
$router->post('banks/store', [
    'uses' => 'ControllerBanks@StoreBank',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('banks/list/', [
    'uses' => 'ControllerBanks@ListBanks',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('banks/get', [
    'uses' => 'ControllerBanks@GetBank',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('banks/delete', [
    'uses' => 'ControllerBanks@BankDelete',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('banks/assign', [
    'uses' => 'ControllerBanks@BankAssign',
    'middleware' => [
        'auth:api',
    ],
]);
$router->get('bundled/feeform', [
    'uses' => 'ControllerBanks@FeeForm',
    'middleware' => [
        'auth:api',
    ],
]);
$router->post('bundled/feeform', [
    'uses' => 'ControllerBanks@FeeForm',
    'middleware' => [
        'auth:api',
    ],
]);
