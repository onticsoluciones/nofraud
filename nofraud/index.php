<?php

use Ontic\NoFraud\Controllers\IController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/vendor/autoload.php';

$routes = new RouteCollection();

$route = new Route('/capabilities', ['controller' => 'Ontic\\NoFraud\\Controllers\\CapabilitiesController']);
$route->setMethods(['GET']);
$routes->add('get_capabilities', $route);

$route = new Route('/assessment', ['controller' => 'Ontic\\NoFraud\\Controllers\\AssesmentController']);
$route->setMethods(['POST']);
$routes->add('create_assessment', $route);

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

$parameters = $matcher->match($request->getPathInfo());
$controllerClass = $parameters['controller'];
/** @var IController $controller */
$controller = new $controllerClass();
$response = $controller->defaultAction();
$response->send();
die;

