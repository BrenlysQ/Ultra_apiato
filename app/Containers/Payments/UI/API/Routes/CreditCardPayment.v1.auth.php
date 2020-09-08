<?php
$router->post('credit_payment/add', [
    'uses' => 'CreditCardController@AddCreditCardTransfer',
    'middleware' => [
        'auth:api',
    ],
]);
