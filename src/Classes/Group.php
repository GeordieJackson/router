<?php

    namespace GeordieJackson\Router\Classes;

    /**
     * Class Group
     *
     * @package GeordieJackson\Router\Classes
     */
    class Group
    {
        /**
         * @var
         */
        protected $callback;
        /**
         * @var
         */
        protected $groupData;

        /**
         * Group constructor.
         */
        public function __construct()
        {
            $this->resetGroupData();
        }

        /**
         *
         */
        public function resetGroupData()
        {
            $this->groupData = [
                'middleware' => [],
                'name' => null,
                'namespace' => null,
                'prefix' => null,
            ];
        }

        /**
         * @param $args
         */
        public function build($args, RouterInstance $router)
        {
            $data = [];
            foreach ($args as $arg) {
                if (is_array($arg)) {
                    $data = $arg;
                }
                if (is_callable($arg)) {
                    $callback = $arg;
                }
            }
            
            $this->setGroupData($data);
            
            if (isset($callback)) {
                call_user_func($callback, $router);
            }
            
            $this->resetGroupData();
        }

        // ======= GETTERS AND SETTERS ============================ //

        /**
         * @param $value
         */
        public function setMiddleware($value)
        {
            $this->groupData['middleware'] = array_merge((array) $this->groupData['middleware'], (array) $value);
        }

        /**
         * @return array
         */
        public function getGroupData() : array
        {
            return $this->groupData;
        }

        /**
         * @param array $data
         */
        protected function setGroupData(array $data) : void
        {
            $keys = array_keys($this->groupData);

            foreach ($data as $key => $value) {
                if ( ! in_array($key, $keys)) {
                    throw new \InvalidArgumentException("Invalid group name ($key) entered");
                }
                if ($key == 'namespace') {
                    $value .= "\\";
                }
                if ($key == 'middleware') {
                    $this->setMiddleware($value);
                } else {
                    $this->groupData[$key] = $value;
                }
            }
        }
    }