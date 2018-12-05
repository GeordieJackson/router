<?php

    namespace GeordieJackson\Router\Classes;

    use GeordieJackson\Router\Exceptions\RouteNotMatchedException;

    /**
     * Class DynamicMatcher
     *
     * @package GeordieJackson\Router\Classes
     */
    class DynamicMatcher implements MatcherInterface
    {
        /**
         * @var array
         */
        protected $pregMatchers = [
            'a' => '[0-9A-Za-z]+',
            'd' => '[0-9A-Za-z- _]+', // default
            'i' => '[0-9]+',
            's' => '[a-z0-9-]+',
        ];

        /**
         * @param string $method
         * @param string $requestUri
         * @param iterable $routes
         * @return mixed
         * @throws \GeordieJackson\Router\Exceptions\RouteNotMatchedException
         */
        public function match(string $method, string $requestUri, iterable $routes)
        {
            $routes = $routes[strtoupper($method)];
            $uriComponents = explode('/', trim($requestUri, "/"));
            $matchesRequired = count($uriComponents);

            foreach ($routes as $route) {
                $path = $route->getPath();
                $pathComponents = explode('/', trim($path, "/"));
                if ($matchesRequired != count($pathComponents)) {
                    continue;
                }
                $matchCount = 0;
                $args = [];
                for ($x = 0; $x < $matchesRequired; $x++) {
                    $addArg = false;
                    $pathComponent = $pathComponents[$x];
                    $uriComponent = $uriComponents[$x];
                    if (stristr($pathComponent, '{')) {
                        $addArg = true;
                        $argKey = $this->getArgKey($pathComponent);
                        $whereConstraint = $route->getWhere($argKey);
                        $regex = $this->selectRegex($whereConstraint, $route, $argKey);
                        $pathComponent = $regex;
                    }
                    if (preg_match('#^' . $pathComponent . '$#', $uriComponent)) {
                        $matchCount++;
                        if ($addArg === true) {
                            $args[$argKey] = $uriComponent;
                        }
                    }
                }
                if ($matchesRequired == $matchCount) {
                    $returnRoute = $routes[$path];
                    $returnRoute->setArguments($args);

                    return $returnRoute->getPath();
                }
            }

            throw new RouteNotMatchedException("Route matching failed: the route '$requestUri' was not found'");
        }

        /**
         * @param $string
         * @return string
         */
        protected function getArgKey($string)
        {
            preg_match("/(\{)(.*?)(\})/", $string, $matches);

            return $matches[2];
        }

        /**
         * @param $key
         * @return mixed
         */
        protected function getPregMatcher($key)
        {
            return $this->pregMatchers[$key];
        }

        /**
         * @param $whereValue
         * @param $route
         * @param $argKey
         * @return string
         */
        protected function selectRegex($whereValue, $route, $argKey)
        {
            if ($whereValue && array_key_exists($route->getWhere($argKey), $this->pregMatchers)) {
                $regex = $this->getPregMatcher($whereValue);
            } elseif (array_key_exists($argKey, $route->getWhere())) {
                $regex = $route->getWhere($argKey);
            } else {
                $regex = $this->getPregMatcher('d'); // default
            }

            return $regex;
        }
    }