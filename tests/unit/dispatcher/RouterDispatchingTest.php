<?php

    namespace dispatcher;

    use GeordieJackson\Router\Classes\Dispatcher;
    use GeordieJackson\Router\Classes\Router;
    use Acclimate\Container\ContainerAcclimator;

    class RouterDispatchingTest extends \Codeception\Test\Unit
    {
        /**
         * @var \UnitTester
         */
        protected $tester, $router, $dispatcher;

        protected function _before()
        {
            $this->router = Router::create();
            $this->router->setDefaultNamespace('GeordieJackson\Router\Tests');
        }

        protected function _after()
        {
        }

        /**
         | -------------------------------------- NOTE -------------------------------------
         |
         |                  These tests only work on *** default dispatching ***
         |
         |             They do NOT test the dispatcher working with containers (!)
         |
        | -----------------------------------------------------------------------------------
         */

        /**
        * @test
        */
        public function Router_can_dispatch_a_static_route()
        {
            $this->router->get('path', 'TestController@test');
            $result = $this->router->dispatch('get', 'path');
            $this->assertEquals('Test passed TD1 here TD2 here', $result);
        }

        /**
         * @test
         */
        public function Router_can_dispatch_a_static_callback_route()
        {
            $this->router->get('path', function () {
                return 'Test passed TD1 here TD2 here';
            });
            $result = $this->router->dispatch('get', 'path');
            $this->assertEquals('Test passed TD1 here TD2 here', $result);
        }

        /**
        * @test
        */
        public function router_can_dispatch_a_matched_static_route()
        {
            $this->router->get('path', 'TestController@test');
            $route = $this->router->match('GET', 'path');
            $result = $this->router->dispatchRoute($route);
            $this->assertEquals('Test passed TD1 here TD2 here', $result);
        }

        /**
         * @test
         */
        public function router_can_dispatch_a_matched_callback_route()
        {
            $this->router->get('path', function () {
                return 'Test passed TD1 here TD2 here';
            });
            $route = $this->router->match('GET', 'path');
            $result = $this->router->dispatchRoute($route);
            $this->assertEquals('Test passed TD1 here TD2 here', $result);
        }

        /**
        * @test
        */
        public function an_ivalid_route_throws_an_exception()
        {
            $this->router->get('invalid-route', 'TestController');
            $this->expectException(\InvalidArgumentException::class);
            $this->router->dispatch('get', 'invalid-route');
        }
    }