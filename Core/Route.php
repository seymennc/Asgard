<?php

namespace Asgard\Core;


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
            $callback = $val['callback'];
            foreach (self::$patterns as $key => $pattern) {
               $path = preg_replace('#' . $key . '#', $pattern, $path);
            }
            $pattern = '@^' . $path . '$@';
            if (preg_match($pattern, $url, $params)){
                array_shift($params);

                self::$hasRoute = true;
                if (is_callable($callback)){
                    echo call_user_func_array($callback, $params);
                }elseif (is_string($callback)){
                    [$controllerStr, $action] = explode('@', $callback);

                    $controllerStr = '\Asgard\App\Controllers\\' . $controllerStr;
                    $controller = new $controllerStr();

                    echo call_user_func_array([$controller, $action], $params);
                }
            }
        }
        self::hasRoute();
    }

    public static function hasRoute(): void
    {
        if (self::$hasRoute === false){
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
        return str_replace(getenv("BASE_PATH"), null, $_SERVER['REQUEST_URI']);
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

        return str_replace(array_keys($params), array_values($params), $route);
    }

    public static function prefix($prefix): Route
    {
        self::$prefix = $prefix;
        return new self();
    }
    public static function group(\Closure $closure){
        $closure();
        self::$prefix = '';
    }
}