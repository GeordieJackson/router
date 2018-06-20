<?php

    namespace GeordieJackson\Router\Classes;

    /**
     * Class Route
     *
     * @package GeordieJackson\Router\Classes
     */
    class Route
    {
        /**
         * @var
         */
        protected static $router;

        /**
         * @param \GeordieJackson\Router\Classes\RouterInstance $router
         * @param                                       $dir
         * @return mixed
         */
        public static function load(RouterInstance $router, $dir)
        {
            static::$router = $router;
            $files = array_diff(scandir($dir), ['.', '..']);
            foreach ($files as $file) {
                include "$dir/" . $file;
            }

            return static::$router;
        }

        /**
         * @param $name
         * @param $arguments
         * @return \GeordieJackson\Router\Classes\Route
         */
        public static function __callStatic($name, $arguments)
        {
            return static::processRoute($name, $arguments);
        }

        /**
         * @param $name
         * @param $arguments
         * @return \GeordieJackson\Router\Classes\Route
         */
        public function __call($name, $arguments)
        {
            return static::processRoute($name, $arguments);
        }

        /**
         * @param $name
         * @param $arguments
         * @return \GeordieJackson\Router\Classes\Route
         */
        protected static function processRoute($name, $arguments)
        {
            $route = new static();
            if (method_exists(static::$router, $name)) {
                call_user_func_array([static::$router, $name], $arguments);
            }

            return $route;
        }
    }