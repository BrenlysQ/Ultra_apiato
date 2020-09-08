<?php

// provider login redirect (WEB)
$router->get('auth/{provider}', [
    'uses' => 'Controller@redirectAll',
]);

// provider callback handler
$router->group(['middlewareGroups' => 'web'], function ($router) {
  $router->any('auth/{provider}/callback', [
      'uses' => 'Controller@handleCallbackAll',
  ]);
});
