 <?php 

$router->post('um/sendmails', [
    'uses' => 'UltraMailerController@sendMails',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('um/fullmail', [
    'uses' => 'UltraMailerController@FullCampaignTable',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('um/fullstatus', [
    'uses' => 'UltraMailerController@FullStatusCampaign',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('um/fullheader ', [
    'uses' => 'UltraMailerController@FullConfigHeader',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('um/mailcampaign ', [
    'uses' => 'UltraMailerController@FullMailCampaign',
    'middleware' => [
        'auth:api',
    ],
]);

$router->post('um/getpeople ', [
    'uses' => 'UltraMailerController@GetPeople',
    'middleware' => [
        'auth:api',
    ],
]);