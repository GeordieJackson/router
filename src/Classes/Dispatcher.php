<?php

    namespace GeordieJackson\Router\Classes;

    use Acclimate\Container\ContainerAcclimator;

    /**
     * Class Dispatcher
     * @package GeordieJackson\Router\Classes
     */
    class Dispatcher
    {
        /**
         * @var
         */
        protected $autowirer;
        /**
         * @var ContainerAcclimator
         */
        protected $acclimator;
        /**
         * @var
         */
        protected $container;

        /**
         * Dispatcher constructor.
         * @param ContainerAcclimator $acclimator
         */
        public function __construct(Autowirer $autowirer, ContainerAcclimator $acclimator)
        {
            $this->autowirer = $autowirer;
            $this->acclimator = $acclimator;
        }

        /**
         * @param RouteInstance $route
         * @return mixed
         */
        public function dispatch(RouteInstance $route)
        {
            if (is_callable($route->getCallback())) {
                return call_user_func_array($route->getCallback(), $route->getArguments());
            }

            if ($route->getController() && $route->getAction()) {
                if ($this->container) {
                    $controllerName = $route->getControllerName();
                    $controller = $this->container->get($controllerName);
                } else {
                    $controllerName = $route->getController();
                    $controller = $this->autowirer->resolve($controllerName);
                }
                $action = $route->getAction();
                $args = $route->getArguments();
                return $controller->$action(...$args);
            }

            throw new \InvalidArgumentException("The route with path ({$route->getPath()}) could not be processed - check its structure.");
        }

        /**
         * @param $container
         */
        public function setContainer($container)
        {
            $this->container = $this->acclimator->acclimate($container);
        }
    }