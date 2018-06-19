<?php

namespace GeordieJackson\Router\Classes;

use GeordieJackson\Router\Exceptions\RouteNotMatchedException;
use function array_keys;
use function in_array;
use function strtoupper;

/**
 * Class StaticMatcher
 *
 * @package GeordieJackson\Router\Classes
 */
class StaticMatcher implements MatcherInterface
{
    /**
     * @param string $method
     * @param string $requestUri
     * @param iterable $routes
     * @return string
     * @throws \GeordieJackson\Router\Exceptions\RouteNotMatchedException
     */
    public function match(string $method, string $requestUri, iterable $routes)
    {
        if (in_array($requestUri, array_keys($routes[strtoupper($method)]))) {
            return $requestUri;
        }

        throw new RouteNotMatchedException("The URI $requestUri could not be matched");
    }
}