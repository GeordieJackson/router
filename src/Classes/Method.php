<?php

    namespace GeordieJackson\Router\Classes;

    use GeordieJackson\Collection\Collection;
    use GeordieJackson\Router\Exceptions\RouteNotMatchedException;

    /**
     * Class Method
     *
     * @package GeordieJackson\Router\Classes
     */
    class Method
    {
        /**
         * @param $name
         */
        public function addName($name, RouterInstance $router)
        {
            if ( ! $routes = Collection::make($router->getCurrentDetails())) {
                return;
            }
            
            $routes->each(function($route) use($name, $router) {
                $route->setName($router->getGroupData('name') . $name);
                $router->addToNamedRoutes($route);
            });
        }

        public function addNamespace($name, RouterInstance $router)
        {
            if ( ! $routes = $router->getCurrentDetails()) {
                return;
            }

            foreach ($routes as $route) {
                if($name[0] == "\\") {
                    $route->setNamespace($name . "\\"); // Absolute routes
                } else {
                    $route->setNamespace($route->getNamespace() . $name . "\\");
                }
            }
        }

        /**
         * @param $middleware
         */
        public function addMiddleware($middleware, RouterInstance $router)
        {
            if ( ! $routes = Collection::make($router->getCurrentDetails())) {
                return;
            }
            
            $routes->map(function($route) use($middleware, $router) {
                $route->setMiddleware(array_merge((array) $router->getGroupData('middleware'),
                    (array) $middleware));
            });
        }

        /**
         * @param      $constraints
         * @param null $single
         */
        public function addWhereClauses($constraints, $single = null, RouterInstance $router)
        {
            if ( ! $routes = Collection::make($router->getCurrentDetails())) {
                return;
            }

            if ( ! is_array($constraints) && ! is_null($single)) {
                $constraints = [$constraints => $single];
            }

            if ( ! is_array($constraints)) {
                throw new \InvalidArgumentException("Arguments must be passed in via an array of ['key1' => 'value1', 'key2' => 'value2'] pairs or a single ('key', 'value') pair.");
            }
            
            $routes->map(function($route) use ($constraints) {
                $route->setWhere($constraints);
            });
        }

        /**
         * @param string $name
         * @param array $params
         * @return string
         * @throws \GeordieJackson\Router\Exceptions\RouteNotMatchedException
         */
        public function url(string $name, array $params = [], RouterInstance $router)
        {
            if ( ! array_key_exists($name, $router->getNamedRoutes())) {
                throw new RouteNotMatchedException("No route with tha name '$name' was found");
            }
            $path = $router->getNamedRoute($name)->getPath();

            /**
             *  Insert any named parameters
             */
            if (count($params)) {
                foreach ($params as $key => $value) {
                    $path = str_replace("{{$key}}", $value, $path);
                }
            }

            /** Always return a slash rather than a blank as a blank will always be displayed as a link to the current directory in the browser */
            if ($path == '' || $path == "/") {
                return '/';
            }

            return "/" . trim($path, "/ ");
        }
    }