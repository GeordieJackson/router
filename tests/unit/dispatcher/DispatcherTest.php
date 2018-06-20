<?php

    namespace dispatcher;

    use GeordieJackson\Router\Classes\Autowirer;
    use Acclimate\Container\ContainerAcclimator;
    use GeordieJackson\Router\Classes\Dispatcher;
    use GeordieJackson\Router\Classes\RouteInstance;

    class DispatcherTest extends \Codeception\Test\Unit
    {
        /**
         * @var \UnitTester
         */
        protected $tester, $route, $dispatcher;

        protected function _before()
        {
            $this->route = new RouteInstance();
            $this->dispatcher = new Dispatcher(new Autowirer(), $this->make($this->make(ContainerAcclimator::class, ['acclimate' => null])));
        }

        protected function _after()
        {
        }

        /**
         * @test
         */
        public function it_can_register_a_container_object()
        {
            $container = new \stdClass();
            $this->dispatcher->setContainer($container);
            $this->assertObjectHasAttribute('container', $this->dispatcher);
        }

        /**
         * @test
         */
        public function it_dispatches_a_callback()
        {
            $route = $this->route;
            $route->setPath('path');
            $route->setCallback(function () {
                return "Hello world!";
            });
            $result = $this->dispatcher->dispatch($route);

            $this->assertEquals("Hello world!", $result);
        }

        /**
         * @test
         */
        public function it_dispatches_a_callback_with_parameters()
        {
            $route = $this->route;
            $route->setPath('path');
            $route->setCallback(function ($forename, $surname) {
                return "Hello, $forename $surname";
            });
            $route->setArguments(['forename' => 'Wapples', 'surname' => 'Chompert']);
            $result = $this->dispatcher->dispatch($route);

            $this->assertEquals("Hello, Wapples Chompert", $result);
        }

        /**
         * @test
         */
        public function it_dispatches_a_controller_action()
        {
            $route = $this->route;
            $route->setController('TestController');
            $route->setAction('test');
            $route->setNamespace('GeordieJackson\Router\Tests\\');
            $result = $this->dispatcher->dispatch($route);
            $this->assertEquals('Test passed TD1 here TD2 here', $result);
        }


    }