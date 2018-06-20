<?php

    namespace GeordieJackson\Router\Classes;

    use Acclimate\Container\ContainerAcclimator;

    /**
     * Class RouterFactory
     *
     * @package GeordieJackson\Router\Classes
     */
    class RouterFactory
    {
        /**
         * @param string $dir
         * @return \GeordieJackson\Router\Classes\Router
         */
        public static function create($dir = null)
        {
            $dependencies = [
                new Group(),
                new Resource(),
                new RouteBuilder(new RouteInstance()),
                new Method(),
                new MatcherFactory(),
                new Dispatcher(new Autowirer(), new ContainerAcclimator())
            ];

            $router = new Router(...$dependencies);

            if ($dir) {
                Route::load($router, $dir);
            }

            return $router;
        }
    }