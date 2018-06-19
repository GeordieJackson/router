<?php

    namespace router;

    use GeordieJackson\Router\Classes\RouterFactory;

    class NamespacingTest extends \Codeception\Test\Unit
    {
        /**
         * @var \UnitTester
         */
        protected $tester;
        protected $router;
        protected $routerWithRoutesLoaded;

        protected function _before()
        {
            $this->router = RouterFactory::create();
            $this->routerWithRoutesLoaded = RouterFactory::create()
                ->setDefaultNamespace('GeordieJackson\Router\Http\Controllers')
                ->load(__DIR__ . "/../../test-routes")
            ;
        }

        protected function _after()
        {
        }

        /**
         * @test
         */
        public function it_can_set_a_default_namespace()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $this->assertEquals('GeordieJackson\Router\Http\Controllers\\', $this->router->getDefaultNamespace());
        }

        /**
         * @test
         */
        public function it_sets_the_route_namespace()
        {
            $this->router->get('blog', 'BlogController@index')->namespace('Individual');
            $this->assertEquals("Individual\\", $this->router->getRoute('GET', 'blog')->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_resource_namespaces()
        {
            $this->router->resource('res', 'ResourceController')->namespace('Resource');
            $route = $this->router->getNamedRoute('res.create');
            $this->assertEquals('Resource\\', $route->getNamespace());
        }

        public function it_sets_a_group_namespace()
        {
            $this->router->group(['namespace' => "GeordieJackson\Router"], function () {
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage');
            });

            $this->assertEquals('GeordieJackson\Router\\', $this->router->getNamedRoute('aboutPage')->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_a_group_namespace_and_an_individual_namespace()
        {
            $this->router->group(['namespace' => "GeordieJackson\Router"], function () {
                $this->router->get('about-us', 'PagesController@about')->namespace('Individual')->name('aboutPage');
            });

            $this->assertEquals('GeordieJackson\Router\Individual\\', $this->router->getNamedRoute('aboutPage')->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_a_group_namespace_and_a_resource_namespace()
        {
            $this->router->group(['namespace' => "GeordieJackson\Router"], function () {
                $this->router->resource('res', 'ResourceController')->namespace('Resource');
            });

            $route = $this->router->getNamedRoute('res.create');
            $this->assertEquals('GeordieJackson\Router\Resource\\', $route->getNamespace());
        }
///////////////////////////

        /**
         * @test
         */
        public function it_sets_default_namespace_and_a_route_namespace()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $this->router->get('blog', 'BlogController@index')->namespace('Individual');
            $this->assertEquals("GeordieJackson\Router\Http\Controllers\Individual\\", $this->router->getRoute('GET', 'blog')->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_default_namespace_and_a_resource_namespace()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $this->router->resource('res', 'ResourceController')->namespace('Resource');
            $route = $this->router->getNamedRoute('res.create');
            $this->assertEquals('GeordieJackson\Router\Http\Controllers\Resource\\', $route->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_default_namespace_and_a_group_namespace()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $this->router->group(['namespace' => "GeordieJackson\Router"], function () {
                $this->router->get('about-us', 'PagesController@about')->name('aboutPage');
            });

            $this->assertEquals('GeordieJackson\Router\Http\Controllers\\GeordieJackson\Router\\', $this->router->getNamedRoute('aboutPage')->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_default_namespace_and_a_group_and_an_individual_namespace()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $this->router->group(['namespace' => "GeordieJackson\Router"], function () {
                $this->router->get('about-us', 'PagesController@about')->namespace('Individual')->name('aboutPage');
            });

            $this->assertEquals('GeordieJackson\Router\Http\Controllers\GeordieJackson\Router\Individual\\', $this->router->getNamedRoute('aboutPage')->getNamespace());
        }

        /**
         * @test
         */
        public function it_sets_default_namespace_and_a_group_and_a_resource_namespace()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $this->router->group(['namespace' => "GeordieJackson\Router"], function () {
                $this->router->resource('res', 'ResourceController')->namespace('Resource');
            });

            $route = $this->router->getNamedRoute('res.create');
            $this->assertEquals('GeordieJackson\Router\Http\Controllers\GeordieJackson\Router\Resource\\', $route->getNamespace());
        }

        /**
        * @test
        */
        public function it_adds_default_namespace_to_file_loaded_routes()
        {
            $this->router->setDefaultNamespace('GeordieJackson\Router\Http\Controllers');
            $route = $this->routerWithRoutesLoaded->getAllRoutes()['GET']['/'];
            $this->assertEquals('GeordieJackson\Router\Http\Controllers\\', $route->getNamespace());
        }

        /**
        * @test
        */
        public function it_preserves_namespaces_set_with_a_backslash_or_absolute_routes()
        {
            $this->router->setDefaultNamespace('Default');

            $this->router->group(['namespace' => "Group"], function () {
                $this->router->get('about-us', 'PagesController@about')->namespace('\\Backslash')->name('aboutPage');
            });

            $this->assertEquals('Backslash\\', $this->router->getNamedRoute('aboutPage')->getNamespace());
        }
    }