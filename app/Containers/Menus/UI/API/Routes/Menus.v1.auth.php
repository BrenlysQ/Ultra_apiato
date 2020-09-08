 <?php 

$router->post('menu/menuadm', [
    'uses' => 'MenusController@fullMenus',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('menu/menurol', [
    'uses' => 'MenusController@fullMenusRoles',
    'middleware' => [
        'auth:api',
    ],
]);