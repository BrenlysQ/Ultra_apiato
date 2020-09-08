<?php
$router->get('invoice/list', [
    'uses' => 'ControllerInvoices@GetInvoiceList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('invoice/info', [
    'uses' => 'ControllerInvoices@InvoiceInfo',
    'middleware' => [
    	'auth:api',
    ],
]);

$router->post('invoice/listSat', [
    'uses' => 'ControllerInvoices@InvoiceListSat',
    'middleware' => [
    	'auth:api',
    ],
]);

$router->post('invoice/issue', [
    'uses' => 'ControllerInvoices@InvoiceIssue',
    'middleware' => [
    	'auth:api',
    ],
]);

$router->post('invoice/changeInvoiceStatus', [
    'uses' => 'ControllerInvoices@changeInvoiceStatus',
    'middleware' => [
    	'auth:api',
    ],
]);
