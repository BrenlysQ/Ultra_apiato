<?php 
$router->post('search_engine/store', [
    'uses' => 'ControllerSearchEngines@StoreSearchEngine',
    'middleware' => [
        'auth:api',
    ],
]);

$router->get('search_engine/list', [
    'uses' => 'ControllerSearchEngines@GetSearchEngineList',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('search_engine/edit', [
    'uses' => 'ControllerSearchEngines@bundledSearch_engine',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('search_engine/delete', [
    'uses' => 'ControllerSearchEngines@deleteSearch_engine',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('search_engine/update', [
    'uses' => 'ControllerSearchEngines@updateSearch_engine',
    'middleware' => [
        'auth:api',
    ],
]);
