<?php

namespace GeordieJackson\Router\Classes;

/**
 * Interface MatcherInterface
 *
 * @package GeordieJackson\Router\Classes
 */
interface MatcherInterface
{
    /**
     * @param string $method
     * @param string $requestUri
     * @param iterable $routes
     * @return mixed
     */
    public function match(string $method, string $requestUri, iterable $routes);
}