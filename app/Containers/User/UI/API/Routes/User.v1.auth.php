<?php
$router->post('User/store', [
    'uses' => 'ControllerUser@StoreUser',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('/register', [
    'uses'  => 'Controller@registerUser',
]);

$router->get('User/list', [
    'uses' => 'ControllerUser@GetUserList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('User/delete', [
    'uses' => 'ControllerUser@DeleteUser',
    'middleware' => [
        'auth:api',
    ],
]);


$router->post('User/Update', [
    'uses' => 'ControllerUser@UpdateUser',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('User/GetRols', [
    'uses' => 'ControllerUser@GetRols',
    'middleware' => [
        'auth:api',
    ],
]);

