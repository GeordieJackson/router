<?php
    
    namespace matching;
    
    use GeordieJackson\Router\Classes\Router;
    use GeordieJackson\Router\Exceptions\RouteNotMatchedException;

    class MatchingWithConstraintsTest extends \Codeception\Test\Unit
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
        public function it_matches_with_the_integer_regex()
        {
            $this->router->get('page/{integer}','BlogController@page')->where('integer', 'i');
            $method = "get";
            $requestUri =  'page/123';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "page");
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_integer_constraint_fails_to_validate()
        {
            $this->router->get('page/{integer}','BlogController@page')->where('integer', 'i');
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'page/string-instead-of-integer';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_matches_with_the_default_regex()
        {
            $this->router->get('blog/{default}','BlogController@blog');
            $method = "get";
            $requestUri =  'blog/This is a Rather_LOOSE-type-of URL 3254';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "blog");
        }
    
        /**
         * @test
         */
        public function even_the_default_regex_will_throw_an_exception_with_bad_characters_in_the_url()
        {
            $this->router->get('blog/{default}','BlogController@blog');
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'blog/This is a ^ Rather_LOOSE-type-of URL 3254';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_matches_with_the_alphanumeric_regex()
        {
            $this->router->get('entry/{alpha}','BlogController@entry')->where(['alpha' => 'a']);
            $method = "get";
            $requestUri =  'entry/ThisHasOnlyAlphaAndNumbers123';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "entry");
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_alphanumeric_constraint_fails_to_validate()
        {
            $this->router->get('entry/{alpha}','BlogController@entry')->where(['alpha' => 'a']);
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'entry/ThisShouldOnlyHaveAlphaAndNumbers123-OopsAHyphen';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_matches_with_the_slug_regex()
        {
            $this->router->get('post/{slug}','BlogController@post')->where(['slug' => 's']);
            $method = "get";
            $requestUri =  'post/this-has-only-alpha-and-numbers-123-plus-hyphens';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "post");
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_slug_constraint_fails_to_validate()
        {
            $this->router->get('post/{slug}','BlogController@post')->where(['slug' => 's']);
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'post/this-has-only-alpha-and-numbers-123-plus-hyphens-but-not_underscores-or-UPPERCASE';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_matches_with_a_user_regex()
        {
            $this->router->get('dynamic/page/{integer}','BlogController@dynamicPage')->where('integer', '[0-7]++');
            $method = "get";
            $requestUri =  'dynamic/page/123';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "dynamicPage");
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_user_constraint_fails_to_validate()
        {
            $this->router->get('dynamic/page/{integer}','BlogController@dynamicPage')->where('integer', '[0-7]++');
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'dynamic/page/128'; // Constraint *should be* set to [0-7]++
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_matches_with_a_multiple_regex()
        {
            $this->router->get('dynamic/{id}/{name}/{id2}/{name2}', 'DynamicController@complex')->where(['id' => '[0-6]++', 'name' => 'a', 'id2' => 'i']);
            $method = "get";
            $requestUri =  'dynamic/123/John/789/Jackson';
            $route = $this->router->match($method, $requestUri);
            $this->assertTrue($route->getAction() == "complex");
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_first_of_multiple_regex_constraint_fails_to_validate()
        {
            $this->router->get('dynamic/{id}/{name}/{id2}/{name2}', 'DynamicController@complex')->where(['id' => '[0-6]++', 'name' => 'a', 'id2' => 'i']);
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'dynamic/129/John/789/Jackson';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_second_of_multiple_regex_constraint_fails_to_validate()
        {
            $this->router->get('dynamic/{id}/{name}/{id2}/{name2}', 'DynamicController@complex')->where(['id' => '[0-6]++', 'name' => 'a', 'id2' => 'i']);
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'dynamic/123/John*M/789/Jackson';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_third_of_multiple_regex_constraint_fails_to_validate()
        {
            $this->router->get('dynamic/{id}/{name}/{id2}/{name2}', 'DynamicController@complex')->where(['id' => '[0-6]++', 'name' => 'a', 'id2' => 'i']);
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'dynamic/123/JohnM/789W/Jackson';
            $this->router->match($method, $requestUri);
        }
    
        /**
         * @test
         */
        public function it_throws_an_exception_if_fourth_of_multiple_regex_constraint_fails_to_validate()
        {
            $this->router->get('dynamic/{id}/{name}/{id2}/{name2}', 'DynamicController@complex')->where(['id' => '[0-6]++', 'name' => 'a', 'id2' => 'i']);
            $this->expectException(RouteNotMatchedException::class);
            $method = 'get';
            $requestUri = 'dynamic/123/JohnM/789/Jackson!';
            $this->router->match($method, $requestUri);
        }
    }