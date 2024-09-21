<?php

namespace Asgard\system\Route;

use Asgard\system\Exceptions\Method\RequestException;

class Methods
{
    /**
     * @param string $method
     * @return RouteBuilder
     */
    public static function method(string $method): RouteBuilder
    {
        Route::$methods[$method] = [];
        return new RouteBuilder();
    }

    /**
     * @param string $name
     * @return void
     * @throws RequestException
     */
    public function name(string $name): void
    {
        $key = array_key_last(Route::$methods);
        if (isset(Route::$namedRoutes[$name])) {
            throw new RequestException("This route name already exists : {$name}");
        }
        $root_path = array_key_last(Route::$routes[$key]);
        Route::$namedRoutes[$name] = [$key, $root_path];
        Route::$pageMethod = [$key];
    }

    /**
     * @param string $prefix
     * @return Route
     */
    public static function prefix(string $prefix): Route
    {
        Route::$prefix = $prefix;
        return new Route();
    }

    /**
     * @param \Closure $closure
     * @return void
     */
    public static function group(\Closure $closure): void
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

class RouteBuilder
{
    /**
     * @param string $path
     * @param string|callable $callback
     * @return MiddlewareBuilder
     * @throws RequestException
     */
    public function route(string $path, string|callable $callback): MiddlewareBuilder
    {
        Handle::addRoute(array_key_last(Route::$methods), $path, $callback);
        return new MiddlewareBuilder();
    }
}

class MiddlewareBuilder
{
    /**
     * @param string[] ...$middlewares
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
     * @return MiddlewareBuilder
     * @throws RequestException
     */
    public function name(string $name): MiddlewareBuilder
    {
        $key = array_key_last(Route::$methods);
        if (isset(Route::$namedRoutes[$name])) {
            throw new RequestException("This route name already exists : {$name}");
        }
        $root_path = array_key_last(Route::$routes[$key]);
        Route::$namedRoutes[$name] = [$key, $root_path];
        Route::$pageMethod = [$key];

        return $this;
    }
}
