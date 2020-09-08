<?php


$router->post('freelance/addFreelance', [
  'uses' => 'ControllerFreelance@addFreelance',
  'middleware' => [
      'auth:api',
  ],
]);


$router->post('freelance/updateFreelance', [
  'uses' => 'ControllerFreelance@updateFreelance',
  'middleware' => [
      'auth:api',
  ],
]);


$router->post('freelance/deleteFreelance', [
  'uses' => 'ControllerFreelance@deleteFreelance',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getFreelance', [
  'uses' => 'ControllerFreelance@getFreelance',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/editFreelance', [
  'uses' => 'ControllerFreelance@editFreelance',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/nearestFreelance', [
  'uses' => 'ControllerFreelance@nearestFreelance',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getReviews', [
  'uses' => 'ControllerFreelance@getReviews',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/addReview', [
  'uses' => 'ControllerFreelance@addReview',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/updateReview', [
  'uses' => 'ControllerFreelance@updateReview',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getInvoices', [
  'uses' => 'ControllerFreelance@getInvoices',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/reviewReminder', [
  'uses' => 'ControllerFreelance@reviewReminder',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getSellsCount', [
  'uses' => 'ControllerFreelance@getSellsCount',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/addCampaign', [
  'uses' => 'ControllerFreelance@addCampaign',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/updateCampaign', [
  'uses' => 'ControllerFreelance@updateCampaign',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/editCampaign', [
  'uses' => 'ControllerFreelance@editCampaign',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/deleteCampaign', [
  'uses' => 'ControllerFreelance@deleteCampaign',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getFees', [
  'uses' => 'ControllerFreelance@getFees',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getBalance', [
  'uses' => 'ControllerFreelance@getBalance',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getFreelanceBankInfo', [
  'uses' => 'ControllerFreelance@getBankInfo',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/createFreelanceBankInfo', [
  'uses' => 'ControllerFreelance@createBankInfo',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/updateFreelanceBankInfo', [
  'uses' => 'ControllerFreelance@updateBankInfo',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/createTeam', [
  'uses' => 'ControllerFreelance@createTeam',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/createPartner', [
  'uses' => 'ControllerFreelance@createPartner',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/getPartnerChart', [
  'uses' => 'ControllerFreelance@getPartnerChart',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('freelance/comparePartners', [
  'uses' => 'ControllerFreelance@comparePartners',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getInvoices', [
  'uses' => 'ControllerFreelance@getInvoices',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getPartners', [
  'uses' => 'ControllerFreelance@getFreelancePartner',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getRankedFreelances', [
  'uses' => 'ControllerFreelance@getRankedFreelances',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/searchFreelances', [
  'uses' => 'ControllerFreelance@searchFreelances',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getFreelancesZone', [
  'uses' => 'ControllerFreelance@getFreelancesZone',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/updateFreelanceStatus', [
  'uses' => 'ControllerFreelance@updateFreelanceStatus',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getFreelanceCountries', [
  'uses' => 'ControllerFreelance@getFreelanceCountries',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('freelance/getInvoiceReview', [
  'uses' => 'ControllerFreelance@getInvoiceReview',
  'middleware' => [
    'auth:api',
  ],
]);
