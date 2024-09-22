<?php

namespace Asgard\system\Route;


use AllowDynamicProperties;

class Methods
{

    /**
     * @param string $method
     * @return RouteBuilder
     */
    public static function method(string $method): RouteBuilder
    {
        $routeBuilder = new RouteBuilder();
        $routeBuilder->setMethod($method);
        return $routeBuilder;
    }

    /**
     * @param string $name
     * @return void
     * @throws \Exception
     */
    public static function name(string $name): void
    {
        $key = RouteBuilder::getCurrentMethod();
        if (isset(Route::$namedRoutes[$name])) {
            throw new \Exception("This route name already exists : {$name}");
        }

        $root_path = array_key_last(Route::$routes[$key]);
        Route::$namedRoutes[$name] = [$key, $root_path];
        Route::$pageMethod = [$key];
    }

    /**
     * @param string $prefix
     * @return Methods
     */
    public static function prefix(string $prefix): Methods
    {
        Route::$prefix = $prefix;
        return new self();
    }

    /**
     * @param \Closure $closure
     * @return void
     */
    public function group(\Closure $closure): void
    {
        $closure();
        Route::$prefix = '';
    }

    /**
     * @param string $key
     * @param string $pattern
     * @return void
     */
    public function where(string $key, string $pattern): void
    {
        Route::$patterns['{' . $key . '}'] = '(' . $pattern . ')';
    }

    /**
     * @param string $from
     * @param string $to
     * @param int $status
     * @return void
     */
    public static function redirect(string $from, string $to, int $status = 301): void
    {
        Route::$routes[Query::getMethods()][$from] = [
            'redirect' => $to,
            'status' => $status,
        ];
    }
}

#[AllowDynamicProperties] class RouteBuilder
{
    private static string $currentMethod;

    public function setMethod(string $method): void
    {
        self::$currentMethod = $method;
    }
    public static function getCurrentMethod(): string
    {
        return self::$currentMethod;
    }

    /**
     * @param string $path
     * @param string|callable $callback
     * @return MiddlewareBuilder
     */
    public function route(string $path, string|callable $callback): MiddlewareBuilder
    {
        Handle::addRoute($this->getCurrentMethod(), $path, $callback);
        return new MiddlewareBuilder();
    }
}

class MiddlewareBuilder
{
    /**
     * @param mixed ...$middlewares
     * @return MiddlewareBuilder
     */
    public function middleware(...$middlewares): MiddlewareBuilder
    {
        $key = array_key_last(Route::$routes[Query::getMethods()]);
        Route::$routes[Query::getMethods()][$key]['middleware'] = $middlewares;
        return $this;
    }

    /**
     * @param string $name
     */
    public function name(string $name): void
    {
        Methods::name($name);
    }
}
