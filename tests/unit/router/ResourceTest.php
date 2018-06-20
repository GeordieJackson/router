<?php
    
    namespace router;
    
    use GeordieJackson\Router\Classes\Router;
    
    class ResourceTest extends \Codeception\Test\Unit
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
        public function it_creates_restful_routes_and_formats_them_properly()
        {
            $path = 'users';
            $singular = 'user';
            $controller = 'UserController';

            $this->router->resource($path, $controller);
            $get = $this->router->getAllRoutes()['GET'];
            $post = $this->router->getAllRoutes()['POST'];
            $put = $this->router->getAllRoutes()['PUT'];
            $delete = $this->router->getAllRoutes()['DELETE'];

            $action = 'index';
            $this->assertArrayHasKey($path, $get); // Asserts request method and path
            $route = $this->router->match('get', $path);
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());

            $action = 'create';
            $this->assertArrayHasKey($path . "/$action", $get); // Asserts request method and path
            $route = $this->router->match('get', $path. "/$action");
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());

            $action = 'store';
            $this->assertArrayHasKey($path, $post); // Asserts request method and path
            $route = $this->router->match('post', $path);
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());

            $action = 'show';
            $this->assertArrayHasKey($path . "/{{$singular}}", $get); // Asserts request method and path
            $route = $this->router->match('get', $path. "/{$singular}");
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());

            $action = 'edit';
            $this->assertArrayHasKey($path . "/{{$singular}}/$action", $get); // Asserts request method and path
            $route = $this->router->match('get', $path. "/{$singular}/$action");
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());

            $action = 'update';
            $this->assertArrayHasKey($path . "/{{$singular}}", $put); // Asserts request method and path
            $route = $this->router->match('put', $path. "/{$singular}");
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());

            $action = 'destroy';
            $this->assertArrayHasKey($path . "/{{$singular}}", $delete); // Asserts request method and path
            $route = $this->router->match('delete', $path. "/{$singular}");
            $this->assertEquals($action, $route->getAction());
            $this->assertEquals($path . ".$action", $route->getName());
        }
        
        /**
        * @test
        */
        public function it_stores_restful_routes_in_the_named_routes_array()
        {
            $this->router->resource('user', 'UserController');
            
            $url = $this->router->url('user.index');
            $this->assertEquals('/user', $url);
    
            $url = $this->router->url('user.store');
            $this->assertEquals('/user', $url);
    
            $url = $this->router->url('user.create');
            $this->assertEquals('/user/create', $url);
    
            $url = $this->router->url('user.show', ['user' => 'john-jackson']);
            $this->assertEquals('/user/john-jackson', $url);
    
            $url = $this->router->url('user.update', ['user' => 'john-jackson']);
            $this->assertEquals('/user/john-jackson', $url);
    
            $url = $this->router->url('user.destroy', ['user' => 'john-jackson']);
            $this->assertEquals('/user/john-jackson', $url);
    
            $url = $this->router->url('user.edit', ['user' => 'john-jackson']);
            $this->assertEquals('/user/john-jackson/edit', $url);
        }
        
        /**
        * @test
        */
        public function restful_routes_work_within_a_group_declaration()
        {
            $this->router->group(['prefix' => 'dashboard', 'name' => 'admin.', 'namespace' => 'Admin'], function ($router) {
                $router->resource('user', 'UserController');
            });
            
            $this->assertArrayHasKey('GET', $this->router->getAllRoutes());
            $this->assertArrayHasKey('POST', $this->router->getAllRoutes());
            $this->assertArrayHasKey('PUT', $this->router->getAllRoutes());
            $this->assertArrayHasKey('DELETE', $this->router->getAllRoutes());
            
            $route = $this->router->match('post', 'dashboard/user');
            $this->assertEquals('admin.user.store', $route->getName());
    
            $route = $this->router->match('get', 'dashboard/user/fred');
            $this->assertEquals('Admin\UserController', $route->getController());
    
            $route = $this->router->match('delete', 'dashboard/user/fred');
            $this->assertEquals('Admin\\', $route->getNamespace());
            
            $url = $this->router->url('admin.user.update', ['user' => 'Fred']);
            $this->assertEquals('/dashboard/user/Fred', $url);
        }
        
        /**
        * @test
        */
        public function restful_routes_work_with_a_default_namespace()
        {
            $this->router->setDefaultNamespace('Default\Namespace');
            
            $this->router->group(['prefix' => 'dashboard', 'name' => 'admin.', 'namespace' => 'Admin'], function ($router) {
                $router->resource('user', 'UserController');
            });
    
            $route = $this->router->match('get', 'dashboard/user/fred');
            $this->assertEquals('Default\Namespace\Admin\UserController', $route->getController());
        }
        
        /**
        * @test
        */
        public function it_adds_group_middleware_to_each_route_created()
        {
            $this->router->group(['prefix' => 'dashboard', 'name' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['one', 'two', 'three']], function ($router) {
                $router->resource('user', 'UserController');
            });
            
            $route = $this->router->match('get', 'dashboard/user/fred');
            $this->assertArraySubset(['one', 'two', 'three'], $route->getMiddleware());
        }
        
        /**
        * @test
        */
        public function it_throws_an_exception_with_an_invalid_controller_argument()
        {
            // This one catches adding an action to the resource controller
            $this->expectException(\InvalidArgumentException::class);
            $this->router->resource('post', 'PostController@store');
        }
    }