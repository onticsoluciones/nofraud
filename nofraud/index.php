<?php

use Ontic\NoFraud\Controllers\BaseController;
use Ontic\NoFraud\Exceptions\AuthenticationFailedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

require_once __DIR__ . '/vendor/autoload.php';

$request = Request::createFromGlobals();
$routes = createRoutes();
$controller = getController($request, $routes);
$response = $controller->defaultAction();
$response->send();

/**
 * @return RouteCollection
 */
function createRoutes()
{
    $routes = new RouteCollection();

    $route = new Route('/capabilities', ['controller' => 'Ontic\\NoFraud\\Controllers\\CapabilitiesController']);
    $route->setMethods(['GET']);
    $routes->add('get_capabilities', $route);

    $route = new Route('/assessment', ['controller' => 'Ontic\\NoFraud\\Controllers\\AssessmentController']);
    $route->setMethods(['POST']);
    $routes->add('create_assessment', $route);

    return $routes;
}

/**
 * @param Request $request
 * @param RouteCollection $routes
 * @return BaseController
 */
function getController(Request $request, RouteCollection $routes)
{
    $context = new RequestContext();
    $context->fromRequest($request);
    $matcher = new UrlMatcher($routes, $context);

    try
    {
        $parameters = $matcher->match($request->getPathInfo());
        $controllerClass = $parameters['controller'];
        $controller = new $controllerClass($request);
        return $controller;
    }
    catch (MethodNotAllowedException $ex)
    {
        http_response_code(405);
        echo '405 Method Not Allowed';
        die;
    }
    catch (ResourceNotFoundException $ex)
    {
        http_response_code(404);
        echo '404 Resource Not Found';
        die;
    }
    catch(AuthenticationFailedException $ex)
    {
        http_response_code(403);
        echo '403 Forbidden';
        die;
    }
}
