<?php

namespace Asgard\system;


use Asgard\App\Helpers\Redirect;
use Asgard\app\Kernel;

class Route
{

    public static array $patterns = [
        ':id[0-9]?' => '([0-9]+)',
        ':url[0-9]?' => '([0-9a-zA-Z-_/]+)',

        '{id[0-9]?}' => '([0-9]+)',
        '{url[0-9]?}' => '([0-9a-zA-Z-_/]+)',
    ];

    public static bool $hasRoute = false;
    public static array $routes = [];
    public static string $prefix = '';
    private static array $namedRoutes = [];

    public static function get($path, $callback): Route
    {
        return self::addRoute('get', $path, $callback);
    }

    public static function post($path, $callback): void
    {
        self::addRoute('post', $path, $callback);
    }

    private static function addRoute($method, $path, $callback): Route
    {
        $formattedPath = '/' . ltrim(self::$prefix . $path, '/');
        self::$routes[$method][$formattedPath] = ['callback' => $callback];
        return new self();
    }

    public static function dispatch(): void
    {
        $url = self::getUrl();
        $method = self::getMethods();

        foreach (self::$routes[$method] as $path => $val) {
            $pattern = self::preparePattern($path);

            if (preg_match($pattern, $url, $params)) {
                self::$hasRoute = true;
                array_shift($params);
                if (isset($val['middleware']) && is_array($val['middleware'])) {
                    $middlewareStack = array_reduce(array_reverse($val['middleware']), function ($next, $middlewareName) {
                        $middlewareClass = Kernel::getMiddlewareClass($middlewareName);
                        $middlewareInstance = new $middlewareClass();
                        return function ($request) use ($middlewareInstance, $next) {
                            return $middlewareInstance->handle($request, $next);
                        };
                    }, function ($request) use ($val, $params) {
                        if (isset($val['redirect'])) {
                            Redirect::to($val['redirect'], $val['status']);
                        } else {
                            self::invokeCallback($val['callback'], $params);
                        }
                    });

                    $request = ['url' => $url, 'method' => $method];
                    $middlewareStack($request);
                } else {
                    if (isset($val['redirect'])) {
                        Redirect::to($val['redirect'], $val['status']);
                    } else {
                        self::invokeCallback($val['callback'], $params);
                    }
                }
                return;
            }
        }
        self::hasRoute();
    }

    private static function preparePattern($path): string
    {
        foreach (self::$patterns as $key => $pattern) {
            $path = preg_replace('#' . $key . '#', $pattern, $path);
        }
        return '#^' . rtrim($path, '/') . '/?$#';
    }

    private static function invokeCallback($callback, $params): void
    {
        if (is_callable($callback)) {
            echo call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            [$controllerStr, $action] = explode('@', $callback);
            $controller = '\Asgard\App\Controllers\\' . $controllerStr;
            echo call_user_func_array([new $controller, $action], $params);
        }
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
        self::$namedRoutes[$name] = $key;
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

    public function where($key, $pattern): void
    {
        self::$patterns['{' . $key . '}'] = '(' . $pattern . ')';
    }

    public static function redirect($from, $to, $status = 301): void
    {
        self::$routes['get'][$from] = [
            'redirect' => $to,
            'status' => $status,
        ];
    }
    public static function route(string $name, string $param = ''): string
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new \Exception("Böyle bir route bulunamadı: {$name}");
        }

        $path = self::$namedRoutes[$name];
        if ($param) {
            $path = explode('/', $path);
            $path = str_replace($path[2], $param, $path);
            $path = implode('/', $path);
        }
        return $path;
    }
    public function middleware(...$middlewares): Route
    {
        $key = array_key_last(self::$routes['get']);
        self::$routes['get'][$key]['middleware'] = $middlewares;
        return new self();
    }
}