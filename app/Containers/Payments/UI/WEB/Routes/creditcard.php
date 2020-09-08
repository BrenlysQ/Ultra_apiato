<?php
$router->post('/creditcard/process', [
    'uses' => 'CreditCard@AddTransaction',
    'middleware' => [
        'auth:web',
    ]
]);
