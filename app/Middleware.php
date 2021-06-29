<?php

namespace App;

use Slim\Exception\NotFoundException;

class Middleware {

  private $app;
  private $db;

  public function __construct($app) {
    $this->app       = $app;
    $this->container = $app->getContainer();
    $this->db        = $container['db'];

    // Security Headers
    $this->app->add(function ($req, $res, $next) {
      $response = $next($req, $res);

      return $response
        ->withHeader('Content-type', 'application/json')
        // avoid clickjacking
        ->withHeader('X-Frame-Options', 'deny')
        // xss protection
        ->withHeader('X-XSS-Protection', '1; mode=block')
        // disable mime sniffing
        ->withHeader('X-Content-Type-Options', 'nosniff')
        // CSP rules
        ->withheader('Content-Security-Policy', "default-src 'self';");
    });

/* $this->app->add(new JwtAuthentication([
'attribute' => 'jwt',
'path'      => '/',
'algorithm' => getenv('JWT_ALGO'),
'secret'    => getenv('JWT_SECRET'),
'callback'  => function ($request, $response, $arguments) {
// check if token has been logged out
$login_info_id = $arguments['decoded']->sub->login_info_id;
$result        = UserLoginInfo::_byId($login_info_id);

if ($result['logged_out'] == '1' && $result['date_logged_out']) {
return false;
} else {
return true;
}

},
'error' => function ($request, $response, $arguments) {
return $response->withJson([
'success' => false,
'error'   => array(
'type'    => AUTH_ERR_TYPE,
'message' => 'Unauthorized access. Token not found.'
),
'action'  => 'Please provide a valid bearer token.'
], 401);
},
'rules' => [
new RequestPathRule([
'path'        => '/',
'passthrough' => ['/auth/signin']
]),
new RequestMethodRule([
'passthrough' => ['OPTIONS']
])
]
])); */

    /**
     * Throw 404 for unknown routes instead of 401 from Authentication
     */
    $this->app->add(function ($request, $response, $next) {

      $route = $request->getAttribute('route');

      if (empty($route)) {
        throw new NotFoundException($request, $response);
      }

      return $next($request, $response);

    });
  }

}
