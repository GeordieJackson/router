<?php
    
    namespace route;
    
    use function count;
    use GeordieJackson\Router\Classes\RouterFactory;

    class RouteTest extends \Codeception\Test\Unit
    {
        /**
         * @var \UnitTester
         */
        protected $tester;
        protected $routesDirectory = __DIR__ . "/../../test-routes";
        protected $namedRoutes;
        protected $router;
        protected $routes;
        
        protected function _before()
        {
            $this->router = RouterFactory::create($this->routesDirectory);
            $this->routes = $this->router->getAllRoutes();
            $this->namedRoutes = $this->router->getNamedRoutes();
        }
        
        /**
         *  Make sure that the routes array is cleared after each test as static values persist (!)
         */
        protected function _after()
        {
        }
        
        /**
         * @test
         */
        public function it_loads_routes_from_a_directory()
        {
            $this->assertArrayHasKey('GET', $this->routes);
        }
    
        /**
         * @test
         */
        public function it_loads_routes_from_first_file_in_routes_directory()
        {
            $this->assertEquals("api/v1/api-1", $this->routes['GET']["api/v1/api-1"]->getPath());
        }
    
        /**
         * @test
         */
        public function it_loads_routes_from_a_subsequent_file_in_routes_directory()
        {
            $this->assertEquals("login", $this->routes['POST']["login"]->getPath() );
        }
    
        /**
         * @test
         */
        public function it_loads_the_data_correctly()
        {
            $this->assertEquals(17, count($this->router->getRoutesOfType('GET')));
            $this->assertEquals(2, count($this->router->getRoutesOfType('POST')));
        }
    }