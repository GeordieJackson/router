<?php
    
    namespace matching;
    
    use GeordieJackson\Router\Classes\Router;
    use GeordieJackson\Router\Exceptions\RouteNotMatchedException;
    use function is_callable;

    class MatchingTest extends \Codeception\Test\Unit
    {
        protected $tester;
        protected $router;
        
        protected function _before()
        {
            $this->router = Router::create();
            
            $routes = [
                'about-us' => 'PageController@about',
                'contact-us' => 'PageController@contact',
                'callable' => function () {
                    echo "This is a callback function!";
                },
                'users' => 'UserController@index',
                'users/{id}' => 'UsersController@show',
                'posts/{name}' => function () {
                    echo "This is the posts callback function!";
                },
                'posts/edit/{postId}' => 'PostsController@edit'
            ];
            
            foreach ($routes as $key => $value) {
                $this->router->get($key, $value);
            }
        }
        
        protected function _after()
        {
        }
        
        /**
         * @test
         */
        public function it_matches_a_static_route()
        {
            $method = 'get';
            $requestUri = 'about-us';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getController() == "PageController");
        }
        
        /**
        * @test
        */
        public function it_handles_a_simple_route_with_a_callback()
        {
            $method = 'GET';
            $requestUri = 'callable';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue(is_callable($route->getCallback()));
        }
        
        /**
        * @test
        */
        public function it_matches_a_dynamic_route()
        {
            $method = 'get';
            $requestUri = 'users/7';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "show");
        }
    
        /**
         * @test
         */
        public function it_matches_a_dynamic_route_with_a_callback()
        {
            $method = 'GET';
            $requestUri = 'posts/callback';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue(is_callable($route->getCallback()));
        }
    
        /**
         * @test
         */
        public function it_matches_a_dynamic_route_with_optional_parameters_and_returns_them_as_arguments()
        {
            $method = 'get';
            $requestUri = 'posts/edit/26';
            $route = $this->router->match($method, $requestUri);
            $this->assertEquals(26, $route->getArguments()[0]);
        }
        
        /**
        * @test
        */
        public function it_matches_a_dynamic_route_with_optional_parameters_and_returns_them_as_named_arguments()
        {
            $method = 'get';
            $requestUri = 'posts/edit/26';
            $route = $this->router->match($method, $requestUri);
            $this->assertEquals(26, $route->getArgumentsWithKeys()['postId']);
        }
        
        /**
        * @test
        */
        public function it_throws_an_exception_when_route_not_matched()
        {
            $this->expectException(RouteNotMatchedException::class);
            $method = 'GET';
            $requestUri = 'route_does_not_exist';
            $route = $this->router->match($method, $requestUri);
        }
    }