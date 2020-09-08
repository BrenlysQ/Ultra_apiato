<?php
$router->post('/bankstransfer/update', [
    'uses' => 'BankTransferController@BtUpdate',
    'middleware' => [
        'auth:api',
    ]
]);

$router->post('bankstransfer/add', [
    'uses' => 'BankTransferController@AddTransfer',
    'middleware' => [
        'auth:api',
    ]
]);


