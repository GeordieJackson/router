<?php


    namespace GeordieJackson\Router\Tests;


    class TestDependency1
    {
        protected $dep2;

        /**
         * TestDependency1 constructor.
         * @param $dep2
         */
        public function __construct(TestDependency2 $dep2)
        {
            $this->dep2 = $dep2;
        }

        public function msg()
        {
            return " TD1 here" . $this->dep2->msg();
        }
    }