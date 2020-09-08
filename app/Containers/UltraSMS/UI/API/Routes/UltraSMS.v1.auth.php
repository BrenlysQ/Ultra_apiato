<?php

$router->post('usms/sendsms', [
    'uses' => 'UltraSmsController@SendSmsTask',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('usms/SendPufSms', [
    'uses' => 'UltraSmsController@sendPufSms',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('usms/GetDataSmsTask', [
    'uses' => 'UltraSmsController@sendPufSms',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('usms/sendmails', [
    'uses' => 'UltraSmsController@FullCampaingSms',
    'middleware' => [
        'auth:api',
    ],
]);