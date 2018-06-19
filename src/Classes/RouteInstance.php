<?php

namespace GeordieJackson\Router\Classes;

/**
 * Class RouteInstance
 *
 * @package GeordieJackson\Router\Classes
 */
class RouteInstance
{
    /**
     * @var
     */
    protected $action;
    /**
     * @var array
     */
    protected $arguments = [];
    /**
     * @var
     */
    protected $callback;
    /**
     * @var
     */
    protected $controller;
    /**
     * @var
     */
    protected $method;
    /**
     * @var array
     */
    protected $middleware = [];
    /**
     * @var
     */
    protected $name;
    /**
     * @var
     */
    protected $namespace;
    /**
     * @var array
     */
    protected $params = [];
    /**
     * @var
     */
    protected $path;
    /**
     * @var array
     */
    protected $where = [];

    /**
     * @param string $key
     * @return array|mixed|string
     */
    public function getWhere(string $key = '')
    {
        if ($key) {
            return $this->where[$key] ?? '';
        }

        return $this->where;
    }

    /**
     * @param array $where
     */
    public function setWhere(array $where): void
    {
        $this->where = $where;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return ltrim($this->namespace, "\\"); // Remove backslash from absolute routes
    }

    /**
     * @param $namespace
     */
    public function setNamespace($namespace): void
    {
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $path
     */
    public function setPath($path): void
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getMiddleware()
    {
        return $this->middleware;
    }

    /**
     * @param $middleware
     */
    public function setMiddleware($middleware): void
    {
        $this->middleware = $middleware;
    }

    public function hasMiddleware()
    {
        return ! empty($this->middleware);
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param $method
     */
    public function setMethod($method): void
    {
        $this->method = $method;
    }

    /**
     * @return mixed
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param $action
     */
    public function setAction($action): void
    {
        $this->action = $action;
    }

    /**
     * @return mixed
     */
    public function getController()
    {
        return $this->namespace . $this->controller;
    }

    /**
     * @return mixed
     */
    public function getControllerName()
    {
        return $this->controller;
    }
    /**
     * @param $controller
     */
    public function setController($controller): void
    {
        $this->controller = $controller;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @param $callback
     */
    public function setCallback($callback): void
    {
        $this->callback = $callback;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param $key
     * @param $value
     */
    public function setParams($key, $value): void
    {
        $this->params[$key] = $value;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        $args = [];
        foreach ($this->arguments as $k => $v) {
            $args [] = $v;
        }
        return $args;
    }

    /**
     * @param $arguments
     */
    public function setArguments($arguments): void
    {
        $this->arguments = $arguments ?? [];
    }

    /**
     * @return array
     */
    public function getArgumentsWithKeys()
    {
        return $this->arguments;
    }
}