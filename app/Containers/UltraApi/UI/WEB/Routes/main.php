<?php

$router->get('/usite/callback', [
    'uses' => 'UapiController@usitecallback',
    'middleware' => [
        'web',
    ]
]);
