<?php

namespace Asgard\system\Route;

use Asgard\system\Exceptions\Method\RequestException;

class Methods
{
    /**
     * @param $method
     * @return Route
     */
    public static function method($method): Route
    {
        Route::$methods[$method] = [];
        return new Route();
    }

    /**
     * @param $path
     * @param $callback
     * @return Route
     * @throws RequestException
     */
    public function route($path, $callback): Route
    {
        return Handle::addRoute(array_key_last(Route::$methods), $path, $callback);
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
     * @param $prefix
     * @return Route
     */
    public static function prefix($prefix): Route
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
     * @param $key
     * @param $pattern
     * @return void
     */
    public function where($key, $pattern): void
    {
        Route::$patterns['{' . $key . '}'] = '(' . $pattern . ')';
    }

    /**
     * @param $from
     * @param $to
     * @param int $status
     * @return void
     */
    public static function redirect($from, $to, int $status = 301): void
    {
        Route::$routes[Query::getMethods()][$from] = [
            'redirect' => $to,
            'status' => $status,
        ];
    }

    /**
     * @param ...$middlewares
     * @return Route
     */
    public function middleware(...$middlewares): Route
    {
        $key = array_key_last(Route::$routes[Query::getMethods()]);
        Route::$routes[Query::getMethods()][$key]['middleware'] = $middlewares;
        return new Route();
    }
}