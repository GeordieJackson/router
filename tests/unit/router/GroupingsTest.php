<?php
    
    namespace router;
    
    use GeordieJackson\Router\Classes\RouteInstance;
    use GeordieJackson\Router\Classes\RouterFactory;

    class GroupingsTest extends \Codeception\Test\Unit
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
        public function it_loads_routes_via_the_group_function()
        {
            $this->router->group([], function () {
                $this->router->get('/', 'PagesController@home');
                $this->router->get('about-us', 'PagesController@about');
            });
            
            $this->assertEquals('about-us', $this->router->getRoute('GET', 'about-us')->getPath());
        }
        
        /**
        * @test
        */
        public function it_works_ok_with_valid_group_names()
        {
            $grouping = [
                'middleware' => [],
                'name' => null,
                'namespace' => null,
                'prefix' => null,
            ];
            
            $this->router->group($grouping, function () {
                $this->router->get('/', 'PagesController@home');
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage');
            });
    
            $this->assertInstanceOf(RouteInstance::class, $this->router->getNamedRoute('aboutPage'));
            
        }
        
        /**
        * @test
        */
        public function it_only_allows_valid_group_names()
        {
            $this->expectException(\InvalidArgumentException::class);
            $this->router->group(['NameNotValid' => 'value'], function () {
                $this->router->get('/', 'PagesController@home');
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage');
            });
        }
        
        /**
        * @test
        */
        public function it_adds_prefix_to_the_route_path()
        {
            $this->router->group(['prefix' => 'admin'], function () {
                $this->router->get('/', 'PagesController@home');
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage');
            });
            
            $this->assertEquals('admin/about-us', $this->router->getNamedRoute('aboutPage')->getPath());
        }
    
        /**
         * @test
         */
        public function it_stores_middleware_names_in_an_array()
        {
            $this->router->group(['middleware' => ['auth', 'admin']], function () {
                $this->router->get('/', 'PagesController@home');
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage')->middleware(['test']);
            });
        
            $this->assertEquals('admin', $this->router->getNamedRoute('aboutPage')->getMiddleware()[1]);
            $this->assertEquals('test', $this->router->getNamedRoute('aboutPage')->getMiddleware()[2]);
        }
    
        /**
         * @test
         */
        public function it_adds_group_name_to_name()
        {
            $this->router->group(['name' => "admin."], function () {
                $this->router->get('/', 'PagesController@home');
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage');
            });
        
            $this->assertEquals('admin.aboutPage', $this->router->getNamedRoute('admin.aboutPage')->getName());
        }
    }