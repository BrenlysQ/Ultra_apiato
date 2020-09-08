<?php
$router->get('payments/list', [
    'uses' => 'ControllerPayments@GetPaymentsList',
    'middleware' => [
    	'auth:api',
    ],
]);

$router->post('payments/chartList', [
    'uses' => 'ControllerPayments@GetPaymentsChartsList',
    'middleware' => [
        'auth:api',
    ],
]);


$router->post('payments/satlist', [
    'uses' => 'ControllerPayments@GetPaymentsSatList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('creditpayments/satlist', [
    'uses' => 'ControllerPayments@GetCreditPaymentsSatList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('creditpayments/list', [
    'uses' => 'ControllerPayments@GetPaymentsCreditList',
    'middleware' => [
    	'auth:api',
    ],
]);

$router->post('creditpayments/info', [
    'uses' => 'ControllerPayments@GetCreditCardInfo',
    'middleware' => [
    	'auth:api',
    ],
]);

$router->get('creditpayment/get', [
    'uses' => 'ControllerPayments@GetCreditCard',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('creditpayments/getById', [
    'uses' => 'ControllerPayments@getPaymentsById',
    'middleware' => [
    	'auth:api',
    ],
]);
