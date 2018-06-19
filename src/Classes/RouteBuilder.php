<?php

    namespace GeordieJackson\Router\Classes;

    /**
     * Class RouteBuilder
     *
     * @package GeordieJackson\Router\Classes
     */
    class RouteBuilder
    {
        /**
         * @var
         */
        protected $route;
        /**
         * @var
         */
        protected $routeInstance;
        /**
         * @var \GeordieJackson\Router\Classes\RouteInstance
         */
        protected $routeInstancePrototype;

        /**
         * RouteBuilder constructor.
         *
         * @param \GeordieJackson\Router\Classes\RouteInstance $routeInstancePrototype
         */
        public function __construct(RouteInstance $routeInstancePrototype)
        {
            $this->routeInstancePrototype = $routeInstancePrototype;
        }

        /**
         * @param string $path
         * @param        $action
         * @param array $params
         * @param string $method
         * @return mixed
         */
        public function build(string $path, $action, array $params, string $method, Router $router)
        {
            $method = strtoupper($method);
            $path = $this->formatPath($path, $router);
            $route = $this->route = clone $this->routeInstancePrototype;
            $route->setPath($path);
            $route->setMethod($method);
            $this->processAction($action);
            $this->processParams($params);
            $route->setNamespace($this->buildNamespace($router));
            $route->setMiddleware($router->getGroupData('middleware'));
            $router->setCurrentDetails($route);
            $router->addToAllRoutes($route);

            return $route;
        }

        /**
         * @param $path
         * @return string
         */
        protected function formatPath(string $path, Router $router) : string
        {
            $path = $path == '' ? "/" : $path;

            if ($router->getGroupData('prefix')) {
                $path = trim($router->getGroupData('prefix') . "/" . $path, "/");
            }

            return $path;
        }

        /**
         * @param $action
         */
        protected function processAction($action) : void
        {
            if (is_callable($action)) {
                $this->route->setCallback($action);
            } elseif (strpos($action, "@")) {
                $fragments = explode("@", $action);
                $this->route->setController($fragments[0]);
                $this->route->setAction($fragments[1]);
            }
        }

        /**
         * @param $params
         */
        protected function processParams(array $params) : void
        {
            foreach ($params as $key => $value) {
                $this->route->setParams($key, $value);
            }
        }

        /**
         * @param $controller
         * @return string
         */
        protected function buildNamespace(Router $router)
        {
            $namespace = null;
            if ($router->getDefaultNamespace()) {
                $namespace = $router->getDefaultNamespace();
            }
            if ($router->getGroupData('namespace')) {
                $namespace .= $router->getGroupData('namespace');
            }

            return $namespace;
        }
    }