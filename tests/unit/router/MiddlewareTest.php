<?php
namespace router;

use GeordieJackson\Router\Classes\RouterFactory;

class MiddlewareTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $router;
    
    protected function _before()
    {
        $this->router = RouterFactory::create();
    }

    protected function _after()
    {
    }
    
    /**
     * @test
     */
    public function it_adds_middleware_to_a_basic_route()
    {
        $this->router->get('blog/{id}','BlogController@show')->name('blog-show')->middleware(['auth', 'test']);
        $route = $this->router->getNamedRoute('blog-show');
        $this->assertEquals('test', $route->getMiddleware()[1]);
    }
    
    /**
    * @test
    */
    public function it_adds_middleware_to_a_resource()
    {
        $this->router->resource('post', 'PostsController')->middleware('test');
        $route = $this->router->getNamedRoute('post.create');
        $this->assertEquals('test', $route->getMiddleware()[0]);
        $route = $this->router->getNamedRoute('post.destroy');
        $this->assertEquals('test', $route->getMiddleware()[0]);
    }
    
    /**
    * @test
    */
    public function it_adds_middleware_to_routes_in_a_group()
    {
        $this->router->group(['prefix' => 'dashboard', 'namespace' => 'Admin', 'name' => 'admin.', 'middleware' => ['UserAuthStatus', 'control']], function ($router) {
            $this->router->get('blog/{id}','BlogController@show')->name('blog.show');
        });
        $route = $this->router->getNamedRoute('admin.blog.show');
        $this->assertArraySubset(['UserAuthStatus', 'control'], $route->getMiddleware());
    }
    
    /**
    * @test
    */
    public function it_adds_group_middleware_to_routes_in_a_resource()
    {
        $this->router->group(['prefix' => 'dashboard', 'namespace' => 'Admin', 'name' => 'admin.', 'middleware' => ['UserAuthStatus', 'control']], function ($router) {
            $this->router->resource('post', 'PostsController')->middleware('test');
        });
        $route = $this->router->getNamedRoute('admin.post.create');
        $this->assertArraySubset(['UserAuthStatus', 'control'], $route->getMiddleware()); // Group
        $this->assertArraySubset(['UserAuthStatus', 'control', 'test'], $route->getMiddleware()); // Group + resource (in correct order)
        $route = $this->router->getNamedRoute('admin.post.destroy');
        $this->assertArraySubset(['UserAuthStatus', 'control'], $route->getMiddleware());
        $this->assertArraySubset(['UserAuthStatus', 'control', 'test'], $route->getMiddleware());
    }
    
    /**
    * @test
    */
    public function subsequent_entries_do_not_pick_up_middleware_previously_set()
    {
        $this->router->get('blog','BlogController@index')->name('blog.index')->middleware('test');
        $this->router->get('blog/{id}','BlogController@show')->name('blog.show');
        $route = $this->router->getNamedRoute('blog.index');
        $this->assertEquals('test', $route->getMiddleware()[0]);
        $route = $this->router->getNamedRoute('blog.show');
        $this->assertTrue(empty($route->getMiddleware()));
    }
    
    /**
    * @test
    */
    public function subsequent_entries_do_not_pick_up_on_resource_middleware_previously_set()
    {
        $this->router->resource('post', 'PostsController')->middleware('test');
        $this->router->get('blog/{id}','BlogController@show')->name('blog.show');
        $route = $this->router->getNamedRoute('post.create');
        $this->assertEquals('test', $route->getMiddleware()[0]);
        $route = $this->router->getNamedRoute('blog.show');
        $this->assertTrue(empty($route->getMiddleware()));
    }
    
    /**
     * @test
     */
    public function subsequent_entries_do_not_pick_up_on_resource_middleware_previously_set_within_groups()
    {
        $this->router->group(['prefix' => 'dashboard', 'namespace' => 'Admin', 'name' => 'admin.', 'middleware' => ['UserAuthStatus', 'control']], function ($router) {
            $this->router->resource('post', 'PostsController')->middleware('test');
            $this->router->get('blog/{id}','BlogController@show')->name('blog.show');
        });
        
        $route = $this->router->getNamedRoute('admin.post.create');
        $this->assertArraySubset(['UserAuthStatus', 'control', 'test'], $route->getMiddleware());
        $route = $this->router->getNamedRoute('admin.blog.show');
        $this->assertEquals(['UserAuthStatus', 'control'], $route->getMiddleware());
    }
    
    /**
     * @test
     */
    public function subsequent_entries_do_not_pick_up_on_resource_middleware_previously_set_in_a_group()
    {
        $this->router->group(['prefix' => 'dashboard', 'namespace' => 'Admin', 'name' => 'admin.', 'middleware' => ['UserAuthStatus', 'control']], function ($router) {
            $this->router->resource('post', 'PostsController')->middleware('test');
        });
        $this->router->get('blog/{id}','BlogController@show')->name('blog.show');
        
        $route = $this->router->getNamedRoute('admin.post.create');
        $this->assertArraySubset(['UserAuthStatus', 'control', 'test'], $route->getMiddleware());
        $route = $this->router->getNamedRoute('blog.show');
        $this->assertTrue(empty($route->getMiddleware()));
    }
}