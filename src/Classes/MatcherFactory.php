<?php

    namespace GeordieJackson\Router\Classes;

    use GeordieJackson\Router\Exceptions\RouteNotMatchedException;

    /**
     * Class MatcherFactory
     *
     * @package GeordieJackson\Router\Classes
     */
    class MatcherFactory
    {
        /**
         * @param $method
         * @param $requestUri
         * @param $routes
         * @return \GeordieJackson\Router\Classes\DynamicMatcher|\GeordieJackson\Router\Classes\StaticMatcher
         * @throws \GeordieJackson\Router\Exceptions\RouteNotMatchedException
         */
        public function getMatcher($method, $requestUri, $routes)
        {
            if (empty($routes)) {
                throw new RouteNotMatchedException("Route matching failed: no routes have been stored.");
            }

            if (array_key_exists($requestUri, $routes[strtoupper($method)])) {
                return new StaticMatcher();
            }

            return new DynamicMatcher();
        }
    }