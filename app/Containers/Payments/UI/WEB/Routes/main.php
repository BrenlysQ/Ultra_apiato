<?php

// $router->get('/payment/view', [
//     'uses' => 'PaymentController@payingview',
// ]);
$router->group(['middleware' => 'weboo'], function ($router) {
  $router->any('/payment/view', [
      'uses' => 'PaymentController@payingview',
  ]);
});

$router->post('/payments/pgateway', [
    'uses' => 'PaymentController@pagteway',
    'middleware' => [
        'auth:web',
    ]
]);
