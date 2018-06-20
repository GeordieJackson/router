<?php
    
    namespace router;
    
    use GeordieJackson\Router\Classes\Router;

    class RouterTest extends \Codeception\Test\Unit
    {
        /**
         * @var \UnitTester
         */
        protected $tester;
        protected $router;
        
        protected function _before()
        {
            $this->router = Router::create();
        }
        
        protected function _after()
        {
        }
    
        /**
        * @test
        */
        public function it_can_add_a_new_static_route()
        {
            $this->router->get('route','RouteController@index');
            $this->assertArrayHasKey('route', $this->router->getRoutesOfType('GET'));
        }
        
        /**
        * @test
         *      This is required for consistency and for reverse look up functionality
        */
        public function it_stores_a_blank_path_as_a_slash_for_the_homepage()
        {
            $this->router->get('','PageController@index');
            $this->assertArrayHasKey('/', $this->router->getRoutesOfType('GET'));
        }
    
        /**
         * @test
         *      This is required for consistency and for reverse look up functionality
         */
        public function it_stores_a_slash_path_as_a_slash_for_the_homepage()
        {
            $this->router->get('/','PageController@index');
            $this->assertEquals('/', $this->router->getRoute('GET', '/')->getPath());
        }
        
        /**
        * @test
        */
        public function it_does_NOT_add_a_slash_to_grouped_routes_when_index_is_blank()
        {
            $this->router->group(['prefix' => 'admin'], function () {
                $this->router->get('','AdminController@index');
                $this->assertArrayHasKey('admin', $this->router->getRoutesOfType('GET'));
            });
        }
    
        /**
         * @test
         */
        public function it_does_NOT_add_a_slash_to_grouped_routes_when_index_is_a_slash()
        {
            $this->router->group(['prefix' => 'admin'], function () {
                $this->router->get('/','AdminController@index');
                $this->assertArrayHasKey('admin', $this->router->getRoutesOfType('GET'));
            });
        }
        
        /**
         * @test
         */
        public function it_can_add_a_dynamic_get_route()
        {
            $uri = 'blog/{id}';
            $action = 'BlogController@show';
    
            $this->router->get($uri,$action);
            $this->assertArrayHasKey($uri, $this->router->getRoutesOfType('GET'));
        }
    
        /**
         * @test
         */
        public function it_can_add_a_route_with_a_callback_action()
        {
            $uri = 'contact-us';
            $action = function () {
                echo "Hello, I'm the contact-us callable";
            };
    
            $this->router->get($uri,$action);
            $this->assertTrue(is_callable($this->router->getRoute('GET', $uri)->getCallback()));
        }
    
        /**
         * @test
         */
        public function it_resolves_controllerAction_into_a_controller_value_and_an_action_value()
        {
            $this->router->get('new','NewController@new');
            $this->assertEquals('NewController', $this->router->getRoute('GET', "new")->getController());
            $this->assertEquals('new', $this->router->getRoute('GET', "new")->getAction());
        }
    
        /**
         * @test
         */
        public function it_resolves_optional_parameters_into_parameter_properties()
        {
            $this->router->get('blog','BlogController@index', ['one' => 1, 'two' => 2]);
            $this->assertEquals(2, $this->router->getRoute('GET', "blog")->getParams()['two']);
        }
    
        /**
         * @test
         */
        public function it_returns_callables_properly()
        {
            $uri = 'callable';
            $action = function () {
                echo "Hello, I'm the callable's output";
            };
    
            $this->router->get($uri,$action);
            $this->assertTrue(is_callable($this->router->getRoute('GET', $uri)->getCallback()));
        }
        
        /**
        * @test
        */
        public function it_stores_named_routes_in_the_namedroutes_array_and_maps_them_to_the_correct_route()
        {
            /**
             * @NOTE: Leave some unnamed routes between named routes to test the method properly
             */
            $this->router->get('blog','BlogController@index')->name('blog-home');
            $this->router->get('blog/{id}','BlogController@show')->name('blog-show');
            $this->router->get('blog/{id}/edit','BlogController@edit')->name('blog-edit');
            $this->router->get('blog/{id}/update','BlogController@update');
            $this->router->get('blog{id}/delete','BlogController@delete')->name('blog-delete');
    
            /**
             *  Make sure that the named route is mapped to th correct entry
             */
            $route = $this->router->getNamedRoute('blog-home');
            $this->assertTrue($route->getAction() == 'index');
            
            $route = $this->router->getNamedRoute('blog-show');
            $this->assertTrue($route->getAction() == 'show');
            
            $route = $this->router->getNamedRoute('blog-delete');
            $this->assertTrue($route->getAction() == 'delete');
        }
        
        /**
        * @test
        */
        public function it_adds_where_clauses()
        {
            $this->router->get('blog/{id}','BlogController@show')->where(['id' => '[0-9]+']);
            $route = $this->router->getRoute('GET', 'blog/{id}');
            $this->assertArrayHasKey('id',$route->getWhere());
        }

        /**
        * @test
        */
        public function it_loads_from_a_file()
        {
            $this->router->load(__DIR__ . "/../../test-routes");
            $this->assertEquals("login", $this->router->getAllRoutes()['POST']["login"]->getPath() );
        }

        /**
        * @test
        */
        public function it_returns_empty_array_when_load_dir_is_not_set()
        {
            $this->router->load();
            $this->assertEmpty($this->router->getAllRoutes());
        }
    }