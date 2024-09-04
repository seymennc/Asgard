<?php

namespace Asgard\system;


use Asgard\App\Helpers\Redirect;

class Route
{

    public static array $patterns = [
        ':id[0-9]?' => '([0-9]+)',
        ':url[0-9]?' => '([0-9a-zA-Z-_/]+)',
    ];
    public static bool $hasRoute = false;
    public static array $routes = [];
    public static string $prefix = '';



    /**
     * @param $path
     * @param $callback
     * @return Route
     */
    public static function get($path, $callback): Route
    {
        self::$routes['get'][self::$prefix . $path] = [
            'callback' => $callback,
        ];
        return new self();
    }

    /**
     * @param $path
     * @param $callback
     * @return void
     */
    public static function post($path, $callback): void
    {
        self::$routes['post'][$path] = [
            'callback' => $callback,
        ];
    }

    public static function dispatch(): void
    {
        $url = self::getUrl();
        $methods = self::getMethods();
        foreach (self::$routes[$methods] as $path => $val) {
            foreach (self::$patterns as $key => $pattern) {
                $path = preg_replace('#' . $key . '#', $pattern, $path);
            }
            $pattern = '#^' . $path . '$#';
            if (preg_match($pattern, $url, $params)) {
                self::$hasRoute = true;
                array_shift($params);

                if (isset($val['redirect'])) {
                    Redirect::to($val['redirect'], $val['status']);
                } else {
                    $callback = $val['callback'];

                    if (is_callable($callback)) {
                        echo call_user_func_array($callback, $params);
                    } elseif (is_string($callback)) {
                        [$controllerStr, $action] = explode('@', $callback);

                        $controllerStr = '\Asgard\App\Controllers\\' . $controllerStr;
                        $controller = new $controllerStr();

                        echo call_user_func_array([$controller, $action], $params);
                    }
                }
            }
        }
        self::hasRoute();
    }

    public static function hasRoute(): void
    {
        if (self::$hasRoute === false) {
            die('Page not found');
        }
    }

    /**
     * @return string
     */
    public static function getMethods(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public static function getUrl(): array|string
    {
        return str_replace(getenv("BASE_PATH"), '', $_SERVER['REQUEST_URI']);
    }

    /**
     * @param string $name
     * @return void
     */
    public function name(string $name): void
    {
        $key = array_key_last(self::$routes['get']);

        self::$routes['get'][$key]['name'] = $name;
    }

    /**
     * @param string $name
     * @param array $params
     * @return array|string|string[]
     */
    public static function url(string $name, array $params = [])
    {
        $route = array_filter(self::$routes['get'], fn($route) => $route['name'] === $name);
        $route = reset($route)['path'] ?? '';

        return str_replace(array_map(fn($key) => ':' . $key, array_keys($params)), array_values($params), $route);
    }

    public static function prefix($prefix): Route
    {
        self::$prefix = $prefix;
        return new self();
    }

    public static function group(\Closure $closure): void
    {
        $closure();
        self::$prefix = '';
    }

    public function where($key, $pattern)
    {
        self::$patterns[':' . $key] = '(' . $pattern . ')';
    }

    public static function redirect($from, $to, $status = 301): void
    {
        self::$routes['get'][$from] = [
            'redirect' => $to,
            'status' => $status,
        ];
    }
}