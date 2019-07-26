<?php

namespace App;

use Slim\Middleware\JwtAuthentication;
use Slim\Middleware\JwtAuthentication\RequestPathRule;
use Slim\Exception\NotFoundException;

class Middleware {

  private $app;
  private $container;

  public function __construct($app) {
    $this->app = $app;
    $this->container = $app->getContainer();

    // Enable Cors
    $this->app->add(function($req, $res, $next) {
      $response = $next($req, $res);
      return $response->withHeader('Access-Control-Allow-Origin', '*')
              ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
              ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE');
    });

    $this->app->add(new JwtAuthentication([
      "attribute" => "jwt",
      "path" => "/",
      "algorithm" => getenv('JWT_ALGO'),
      "secret" => getenv('SECRET_KEY'),
      "error" => function ($request, $response, $arguments) {
          return $response->withJson([
              'success' => false,
              'errors' => $arguments["message"]
          ], 401);
      },
      "rules" => [
        new RequestPathRule([
          'ignore' => ["/public/login"]
        ])
      ]
      // "before" => function ($request, $arguments) {
      //     $user = \App\Models\User::find($arguments['decoded']['sub']);
      //     return $request->withAttribute("user", $user);
      // }
    ]));

    /**
     * Throw 404 for unknown routes instead of 401 from Authentication
     */
    $this->app->add(function($request, $response, $next) {

      $route = $request->getAttribute("route");

      if (empty($route)) {
        throw new NotFoundException($request, $response);
      }

      return $next($request, $response);

    });
  }
}