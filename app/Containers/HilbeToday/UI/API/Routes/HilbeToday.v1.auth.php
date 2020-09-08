<?php 

$router->get('dt/getDollar', [
    'uses' => 'ControllerHilbeToday@getPrice',
    'middleware' => [
        'auth:api',
    ],
]);