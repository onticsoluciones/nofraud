<?php

use Ontic\NoFraud\Controllers\BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/vendor/autoload.php';

$routes = new RouteCollection();

$route = new Route('/capabilities', ['controller' => 'Ontic\\NoFraud\\Controllers\\CapabilitiesController']);
$route->setMethods(['GET']);
$routes->add('get_capabilities', $route);

$route = new Route('/assessment', ['controller' => 'Ontic\\NoFraud\\Controllers\\AssessmentController']);
$route->setMethods(['POST']);
$routes->add('create_assessment', $route);

$request = Request::createFromGlobals();

$context = new RequestContext();
$context->fromRequest($request);
$matcher = new UrlMatcher($routes, $context);

try
{
    $parameters = $matcher->match($request->getPathInfo());
    $controllerClass = $parameters['controller'];
    /** @var BaseController $controller */
    $controller = new $controllerClass($request);
    $response = $controller->defaultAction();
    $response->send();
    die;
}
catch (MethodNotAllowedException $ex)
{
    header('', true, 405);
    echo '405 Method Not Allowed';
    die;
}
catch (ResourceNotFoundException $ex)
{
    header('', true, 404);
    echo '404 Resource Not Found';
    die;
}
