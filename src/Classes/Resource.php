<?php

namespace GeordieJackson\Router\Classes;

use Doctrine\Common\Inflector;

/**
 * Class Resource
 *
 * @package GeordieJackson\Router\Classes
 */
class Resource
{
    /**
     * @param $path
     * @param $controller
     */
    public function build($path, $controller, Router $router)
    {
        if (strpos($controller, "@")) {
            throw new \InvalidArgumentException("A controller action should not be set on a resource: use ControllerName, not ControllerName@action ");
        }

        $singular = Inflector\Inflector::singularize($path);
        $routes = [];

        $routes[] = $router->getRouteBuilder()->build($path, $controller . '@index', [], 'GET', $router);
        $router->name($path . '.index');

        $routes[] = $router->getRouteBuilder()->build($path, $controller . '@store', [], 'POST', $router);
        $router->name($path . '.store');

        $routes[] = $router->getRouteBuilder()->build($path . "/create", $controller . '@create', [], 'GET', $router);
        $router->name($path . '.create');

        $routes[] = $router->getRouteBuilder()->build($path . "/{" . $singular . "}", $controller . '@show', [],
            'GET', $router);
        $router->name($path . '.show');

        $routes[] = $router->getRouteBuilder()->build($path . "/{" . $singular . "}", $controller . '@update', [],
            'PUT', $router);
        $router->name($path . '.update');

        $routes[] = $router->getRouteBuilder()->build($path . "/{" . $singular . "}", $controller . '@destroy', [],
            'DELETE', $router);
        $router->name($path . '.destroy');

        $routes[] = $router->getRouteBuilder()->build($path . "/{" . $singular . "}/edit", $controller . '@edit', [],
            'GET', $router);
        $router->name($path . '.edit');

        $router->setCurrentDetails($routes);
    }
}