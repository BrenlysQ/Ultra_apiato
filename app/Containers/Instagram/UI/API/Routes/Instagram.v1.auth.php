<?php
$router->get('i/getComments', [
    'uses' => 'ControllerInstagram@getComments',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('i/getMessages', [
    'uses' => 'ControllerInstagram@getMessages',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('i/obtainMessages', [
    'uses' => 'ControllerInstagram@obtainMessages',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('i/getLikes', [
    'uses' => 'ControllerInstagram@getLikes',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('i/sendMessage', [
    'uses' => 'ControllerInstagram@sendMessage',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('i/getTimeLine', [
    'uses' => 'ControllerInstagram@getTimeline',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('i/getDefMessages', [
    'uses' => 'ControllerInstagram@getDefMessages',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('i/changeStatus', [
    'uses' => 'ControllerInstagram@changeStatus',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('i/listConstacts', [
    'uses' => 'ControllerInstagram@listContacts',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('i/redirectClient', [
    'uses' => 'ControllerInstagram@redirectClient',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('i/getNotifications', [
    'uses' => 'ControllerInstagram@getNotifications',
    'middleware' => [
        'auth:api',
    ],
]);
