<?php

$router->post('itineraries/remindSendPnr', [
  'uses' => 'ControllerItineraries@remindSendPnr',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/updatePnr', [
  'uses' => 'ControllerItineraries@updatePnr',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/createOfficeItinerary', [
  'uses' => 'ControllerItineraries@createOfficeItinerary',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/checkOfficeItineraries', [
  'uses' => 'ControllerItineraries@checkOfficeItineraries',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/getOfficeUserItineraries', [
  'uses' => 'ControllerItineraries@getOfficeUserItineraries',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/getOfficeItineraries', [
  'uses' => 'ControllerItineraries@getOfficeItineraries',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/createAirlineBalance', [
  'uses' => 'ControllerItineraries@createAirlineBalance',
  'middleware' => [
      'auth:api',
  ],
]);

$router->post('itineraries/updateAirlineBalance', [
  'uses' => 'ControllerItineraries@updateAirlineBalance',
  'middleware' => [
      'auth:api',
  ],
]);
