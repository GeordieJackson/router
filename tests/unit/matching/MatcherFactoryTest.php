<?php
    
    namespace matching;
    
    use GeordieJackson\Router\Classes\DynamicMatcher;
    use GeordieJackson\Router\Classes\MatcherFactory;
    use GeordieJackson\Router\Classes\Router;
    use GeordieJackson\Router\Classes\StaticMatcher;

    class MatcherFactoryTest extends \Codeception\Test\Unit
    {
        protected $tester;
        protected $router;
        protected $routes;
        protected $matcherFactory;
        
        protected function _before()
        {
            $this->router = Router::create();
            $this->matcherFactory = new MatcherFactory();
            $this->router->get('about-us', 'PagesController@about');
            $this->router->get('users/{id}', 'UsersController@show');
        }
        
        protected function _after()
        {
        }
        
        /**
        * @test
        */
        public function it_returns_the_static_matcher_for_a_static_route()
        {
            $factory = $this->matcherFactory->getMatcher('get', 'about-us', $this->router->getAllRoutes());
            $this->assertInstanceOf(StaticMatcher::class, $factory);
        }
    
        /**
         * @test
         */
        public function it_returns_the_dynamic_matcher_for_a_dynamic_route()
        {
            $factory = $this->matcherFactory->getMatcher('get', 'users/7', $this->router->getAllRoutes());
            $this->assertInstanceOf(DynamicMatcher::class, $factory);
        }
    }