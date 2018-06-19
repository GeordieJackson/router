<?php

    namespace GeordieJackson\Router\Classes;

    use ReflectionClass;

    /**
     * Class Autowirer
     * @package GeordieJackson\Router\Classes
     */
    class Autowirer
    {
        /**
         * @param $className
         * @return object
         * @throws \ReflectionException
         */
        public function resolve($className)
        {
            if ( ! class_exists($className)) {
                throw new \InvalidArgumentException("The class $className does not exist.");
            }

            $reflectionClass = new ReflectionClass($className);

            if ( ! ($reflectionClass->getConstructor())) {
                return new $className;
            }

            $params = $reflectionClass->getConstructor()->getParameters();

            if (empty($params)) {
                return new $className;
            }

            $newInstanceParams = [];

            foreach ($params as $param) {
                if (is_null($param->getClass())) {
                   throw new \InvalidArgumentException("Dependency '{$param->getName()}'' in Class '{$param->getDeclaringClass()->getShortName()}'' is not type hinted and so cannot be resolved.");
                } else {
                    $newInstanceParams[] = $this->resolve($param->getClass()->getName());
                }
            }

            return $reflectionClass->newInstanceArgs($newInstanceParams);
        }
    }