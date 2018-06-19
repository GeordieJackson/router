<?php


    namespace GeordieJackson\Router\Tests;


    class TestController
    {
        protected $dep1;

        /**
         * TestController constructor.
         * @param $dep1
         */
        public function __construct(TestDependency1 $dep1)
        {
            $this->dep1 = $dep1;
        }


        public function test()
        {
            return "Test passed" . $this->dep1->msg();
        }
    }