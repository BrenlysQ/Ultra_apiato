<?php

//Ruta de prueba para token y planes
$router->post('insurance/GetPlans', [
  'uses' => 'ControllerInsurance@GetPlans',
  'middleware' => [
      'auth:api',
  ],
]);

//Ruta para generar cotizaciones

$router->post('insurance/GenerateCot', [
  'uses' => 'ControllerInsurance@GenerateCot',
  'middleware' => [
      'auth:api',
  ],
]);
$router->post('insurance/GetQuotation', [
  'uses' => 'ControllerInsurance@GetQuotation',
  'middleware' => [
      'auth:api',
  ],
]);

//Ruta para ver las coberturas de los planes

$router->post('insurance/Coverage', [
  'uses' => 'ControllerInsurance@ViewCoverage',
  'middleware' => [
    'auth:api',
  ],
]);
$router->post('insurance/BookInsurance', [
  'uses' => 'ControllerInsurance@BookInsurance',
  'middleware' => [
    'auth:api',
  ],
]);

$router->post('insurance/getInsuCharts', [
  'uses' => 'ControllerInsurance@getInsuCharts',
  'middleware' => [
    'auth:api',
  ],
]);


$router->post('insurance/getInsuranceReport', [
  'uses' => 'ControllerInsurance@getInsuranceReport',
  'middleware' => [
    'auth:api',
  ],
]);