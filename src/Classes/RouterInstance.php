<?php

    namespace GeordieJackson\Router\Classes;

    use GeordieJackson\Router\Exceptions\RouteNotMatchedException;

    /**
     * Class Router
     *
     * @package GeordieJackson\Router\Classes
     */
    class RouterInstance
    {
        /**
         * @var
         */
        protected $group;
        /**
         * @var
         */
        protected $method;
        /**
         * @var
         */
        protected $resource;
        /**
         * @var Dispatcher
         */
        protected $dispatcher;
        /**
         * @var
         */
        protected $routeBuilder;
        /**
         * @var array
         */
        protected $allRoutes = [];
        /**
         * @var
         */
        protected $currentDetails;
        /**
         * @var MatcherFactory
         */
        protected $matcherFactory;
        /**
         * @var array
         */
        protected $namedRoutes = [];
        /**
         * @var
         */
        protected $defaultNamespace;

        /**
         * Router constructor.
         * @param $group
         * @param $resource
         * @param $routeBuilder
         * @param $method
         * @param MatcherFactory $matcherFactory
         * @param Dispatcher $dispatcher
         */
        public function __construct(
            Group $group,
            Resource $resource,
            RouteBuilder $routeBuilder,
            Method $method,
            MatcherFactory $matcherFactory,
            Dispatcher $dispatcher
        ) {
            $this->group = $group;
            $this->resource = $resource;
            $this->routeBuilder = $routeBuilder;
            $this->method = $method;
            $this->matcherFactory = $matcherFactory;
            $this->dispatcher = $dispatcher;
        }

        /**
         * @param string $dir
         * @return $this
         */
        public function load($dir = null)
        {
            if ($dir) {
                Route::load($this, $dir);
            }

            return $this;
        }

        // ================== METHOD CALLS ===================== //

        /**
         * @return $this
         */
        public function group()
        {
            $this->group->build(func_get_args(), $this);

            return $this;
        }

        /**
         * @param $path
         * @param $controller
         * @return $this
         */
        public function resource($path, $controller)
        {
            $this->resource->build($path, $controller, $this);

            return $this;
        }

        /**
         * @param $name
         * @return $this
         */
        public function name($name)
        {
            $this->method->addName($name, $this);

            return $this;
        }

        /**
         * @param $middleware
         * @return $this
         */
        public function middleware($middleware)
        {
            $this->method->addMiddleware($middleware, $this);

            return $this;
        }

        /**
         * @param $constraints
         * @param null $single
         * @return $this
         */
        public function where($constraints, $single = null)
        {
            $this->method->addWhereClauses($constraints, $single, $this);

            return $this;
        }

        /**
         * @param $name
         * @return $this
         */
        public function namespace($name)
        {
            $this->method->addNamespace($name, $this);

            return $this;
        }

        /**
         * @param string $name
         * @param array $params
         * @return mixed
         */
        public function url(string $name, array $params = [])
        {
            return $this->method->url($name, $params, $this);
        }

        // ================== ROUTE MATCHING ==================== //

        /**
         * @param string $method
         * @param string $requestUri
         * @return mixed
         * @throws RouteNotMatchedException
         */
        public function match(string $method, string $requestUri)
        {
            $method = strtoupper($method);
            $requestUri = $this->sanitize($requestUri);
            $matcher = $this->matcherFactory->getMatcher($method, $requestUri, $this->allRoutes);
            if ($path = $matcher->match($method, $requestUri, $this->allRoutes)) {
                return $this->allRoutes[$method][$path];
            }

            throw new RouteNotMatchedException("The url '$requestUri' could not be found.");
        }

        /**
         * @param string $method
         * @param string $requestUri
         * @return mixed
         * @throws RouteNotMatchedException
         */
        public function dispatch(string $method, string $requestUri)
        {
            $route = $this->match($method, $requestUri);
            return $this->dispatchRoute($route);
        }

        /**
         * @param RouteInstance $route
         * @return mixed
         */
        public function dispatchRoute(RouteInstance $route)
        {
            return $this->dispatcher->dispatch($route);
        }

        /**
         * @param $requestUri
         * @return mixed
         */
        protected function sanitize($requestUri)
        {
            return filter_var($requestUri, FILTER_SANITIZE_URL);
        }

        // ================== INDIVIDUAL ROUTE METHODS ============= //

        /**
         * @param $path
         * @param $action
         * @param array $params
         * @return $this
         */
        public function get($path, $action, $params = [])
        {
            $this->buildRoute($path, $action, $params, 'GET');

            return $this;
        }

        /**
         * @param $path
         * @param $action
         * @param array $params
         * @return $this
         */
        public function post($path, $action, $params = [])
        {
            $this->buildRoute($path, $action, $params, 'POST');

            return $this;
        }

        /**
         * @param $path
         * @param $action
         * @param array $params
         * @return $this
         */
        public function put($path, $action, $params = [])
        {
            $this->buildRoute($path, $action, $params, 'PUT');

            return $this;
        }

        /**
         * @param $path
         * @param $action
         * @param array $params
         * @return $this
         */
        public function patch($path, $action, $params = [])
        {
            $this->buildRoute($path, $action, $params, 'PATCH');

            return $this;
        }

        /**
         * @param $path
         * @param $action
         * @param array $params
         * @return $this
         */
        public function delete($path, $action, $params = [])
        {
            $this->buildRoute($path, $action, $params, 'DELETE');

            return $this;
        }

        /**
         * @param $path
         * @param $action
         * @param array $params
         * @return $this
         */
        public function options($path, $action, $params = [])
        {
            $this->buildRoute($path, $action, $params, 'OPTIONS');

            return $this;
        }

        /**
         * @param string $path
         * @param $action
         * @param array $params
         * @param string $method
         * @return $this
         */
        protected function buildRoute(string $path, $action, array $params, string $method)
        {
            $this->routeBuilder->build($path, $action, $params, $method, $this);

            return $this;
        }

        // ======= GETTERS AND SETTERS ============================ //

        /**
         * @return array
         */
        public function getAllRoutes() : array
        {
            return $this->allRoutes;
        }

        /**
         * @param $type
         * @return mixed
         */
        public function getRoutesOfType($type)
        {
            if ($routes = $this->allRoutes[$type]) {
                return $routes;
            }

            throw new \InvalidArgumentException("The routes of type [$type] were not found.");
        }

        /**
         * @param $method
         * @param $path
         * @return mixed
         */
        public function getRoute($method, $path)
        {
            if ($route = $this->allRoutes[$method][$path]) {
                return $route;
            }

            throw new \InvalidArgumentException("The route [$method, $path] was not found.");
        }

        /**
         * @param RouteInstance $route
         */
        public function addToAllRoutes(RouteInstance $route)
        {
            $this->allRoutes[$route->getMethod()][$route->getPath()] = $route;
        }

        /**
         * @return array
         */
        public function getNamedRoutes() : array
        {
            return $this->namedRoutes;
        }

        /**
         * @param string $key
         * @return mixed
         */
        public function getNamedRoute(string $key)
        {
            if (array_key_exists($key, $this->namedRoutes)) {
                return $this->namedRoutes[$key];
            }

            throw new \InvalidArgumentException("The namedRoutes array does not contain the key: $key");
        }

        /**
         * @param RouteInstance $route
         */
        public function addToNamedRoutes(RouteInstance $route)
        {
            $this->namedRoutes[$route->getName()] = $route;
        }

        /**
         * @param string $key
         * @return mixed
         */
        public function getGroupData(string $key = '')
        {
            if ( ! $key) {
                return $this->group->getGroupData();
            }

            if (array_key_exists($key, $this->getGroupData())) {
                return $this->getGroupData()[$key];
            }

            throw new \InvalidArgumentException("The group data array does not contain the key: $key");
        }

        /**
         * @param string $defaultNamespace
         * @return $this
         */
        public function setDefaultNamespace(string $defaultNamespace)
        {
            $this->defaultNamespace = $defaultNamespace . "\\";

            return $this;
        }

        /**
         * @return mixed
         */
        public function getDefaultNamespace()
        {
            return $this->defaultNamespace;
        }

        /**
         * @return mixed
         */
        public function getRouteBuilder()
        {
            return $this->routeBuilder;
        }

        /**
         * @return mixed
         */
        public function getCurrentDetails()
        {
            return $this->currentDetails;
        }

        /**
         * @param $currentDetails
         */
        public function setCurrentDetails($currentDetails) : void
        {
            unset($this->currentDetails);
            if (is_array($currentDetails)) {
                $this->currentDetails = $currentDetails;
            } else {
                $this->currentDetails [] = $currentDetails;
            }
        }

        /**
         * @param $container
         */
        public function setContainer($container)
        {
            $this->dispatcher->setContainer($container);
        }
    }