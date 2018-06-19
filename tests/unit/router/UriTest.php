<?php
    
    namespace router;
    
    use GeordieJackson\Router\Classes\RouterFactory;

    class UriTest extends \Codeception\Test\Unit
    {
        /**
         * @var \UnitTester
         */
        protected $tester;
        protected $router;
        protected $uris = [];
        
        protected function _before()
        {
            $this->router = RouterFactory::create();
    
            $this->router->get('entry/{name}','BlogController@show')->where(['name' => 'a'])->name('blog-show');
            $this->router->get('blog/{name}','BlogController@show')->name('blog-show');
            $this->router->get('page/{id}','BlogController@show')->name('blog-show')->where(['id' => 'i']);
            $this->router->get('post/{slug}','BlogController@show')->name('blog-show')->where(['slug' => 's']);
        }
        
        protected function _after()
        {
        }
        
        /**
        * @test
        */
        public function it_returns_the_home_page_url_correctly_from_blank()
        {
            $this->router->get('', 'PagesController@home')->name('homePage');
            $this->assertEquals('/', $this->router->url('homePage'));
            $this->assertNotEquals('', $this->router->url('homePage'));
            $this->assertNotEquals('//', $this->router->url('homePage'));
        }
    
        /**
         * @test
         */
        public function it_returns_the_home_page_url_correctly_from_slash()
        {
            $this->router->get('/', 'PagesController@home')->name('homePage');
            $this->assertEquals('/', $this->router->url('homePage'));
            $this->assertNotEquals('//', $this->router->url('homePage'));
            $this->assertNotEquals('', $this->router->url('homePage'));
        }
    
        /**
         * @test
         */
        public function it_matches_a_named_route_and_returns_its_path()
        {
            $this->router->get('blog','BlogController@index')->name('blog-home');
            $this->assertEquals('/blog', $this->router->url('blog-home'));
        }
    
        /**
         * @test
         */
        public function it_matches_a_named_route_with_an_optional_parameter_and_returns_its_path_with_the_parameter_inserted()
        {
            $this->router->get('blog/{id}','BlogController@show')->name('blog-show');
            $this->assertEquals('/blog/123', $this->router->url('blog-show', ['id' => 123]));
        }
    
        /**
         * @test
         */
        public function it_matches_a_named_route_with_optional_parameters_and_returns_its_path_with_the_parameters_inserted()
        {
            $this->router->get('blog/{id}/{category}/{slug}/edit','BlogController@show')->name('blog-edit');
            $this->assertEquals('/blog/123/science/this-is-a-science-post/edit', $this->router->url('blog-edit', ['id' => 123, 'category' => 'science', 'slug' => 'this-is-a-science-post']));
        }
    }