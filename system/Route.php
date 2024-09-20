<?php

namespace Asgard\system;


use Asgard\App\Helpers\Redirect;
use Asgard\app\Kernel;
use Asgard\app\Middlewares\middlewares;
use Asgard\system\Exceptions\Method\MethodNotAllowedException;
use Asgard\system\Exceptions\Method\PageNotFoundException;

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
    private static array $methods = [];
    private static array $pageMethod = [];

    public static function method($method): Route
    {
        self::$methods[$method] = [];
        return new self();
    }

    /**
     * @throws \Exception
     */
    public function route($path, $callback): Route
    {
        return self::addRoute(array_key_last(self::$methods), $path, $callback);
    }

    private static function addRoute($method, $path, $callback): Route
    {
        $formattedPath = '/' . ltrim(self::$prefix . $path, '/');
        if (isset(self::$routes[$method][$formattedPath])) {
            throw new \Exception("This route already exists: {$formattedPath}");
        }
        self::$routes[$method][$formattedPath] = ['callback' => $callback];
        return new self();
    }

    /**
     * @throws \ReflectionException
     * @throws \Exception
     */
    public static function dispatch(): void
    {
        $url = self::getUrl();
        $method = self::getMethods();

        foreach (self::$routes as $httpMethod => $routes) {
            foreach ($routes as $path => $val) {
                $pattern = self::preparePattern($path);

                if (preg_match($pattern, $url, $params)) {
                    self::$hasRoute = true;
                    self::hasPost($httpMethod, $method);

                    array_shift($params);
                    self::handleMiddleware($val, $url, $method, $params);

                    return;
                }
            }
        }
        self::hasRoute();
    }


    private static function matchRoute($path, $url, &$params): bool
    {
        $pattern = self::preparePattern($path);
        return preg_match($pattern, $url, $params);
    }

    /**
     * @throws \Exception
     */
    private static function handleMiddleware($val, $url, $method, $params): void
    {
        $globalMiddleware = Middlewares::getDefaultMiddleware();
        $allMiddleware = array_merge($globalMiddleware, $val['middleware'] ?? []);

        $middlewareStack = array_reduce(
            array_reverse($allMiddleware),
            function ($next, $middlewareName) {
                $middlewareClass = Kernel::getMiddlewareClass($middlewareName);
                $middlewareInstance = new $middlewareClass();
                return function (Request $request) use ($middlewareInstance, $next) {
                    return $middlewareInstance->handle($request, $next);
                };
            },
            function (Request $request) use ($val, $params) {
                self::handleRoute($val, $params);
            }
        );

        $request = new Request();

        $request->url = $url;
        $request->method = $method;

        $middlewareStack($request);
    }





    private static function handleRoute($val, $params): void
    {
        if (isset($val['redirect'])) {
            Redirect::to($val['redirect'], $val['status']);
        } else {
            self::invokeCallback($val['callback'], $params);
        }
    }


    private static function preparePattern($path): string
    {
        foreach (self::$patterns as $key => $pattern) {
            $path = preg_replace('#' . $key . '#', $pattern, $path);
        }
        return '#^' . rtrim($path, '/') . '/?$#';
    }

    /**
     * @throws \ReflectionException
     */
    private static function invokeCallback($callback, $params): void
    {
        if (is_callable($callback)) {
            echo call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            [$controllerStr, $action] = explode('@', $callback);
            $controller = '\Asgard\App\Controllers\\' . $controllerStr;

            $reflectionMethod = new \ReflectionMethod($controller, $action);
            $methodParams = [];

            foreach ($reflectionMethod->getParameters() as $parameter) {
                $paramType = $parameter->getType();
                if ($paramType && !$paramType->isBuiltin()) {
                    $className = $paramType->getName();
                    $methodParams[] = new $className();
                } else {
                    $methodParams = array_merge($methodParams, $params);
                }
            }

            echo call_user_func_array([new $controller, $action], $methodParams);
        }
    }

    public static function hasRoute(): void
    {
        if (!self::$hasRoute) {
            die(throw new PageNotFoundException());
        }
    }
    public static function hasPost($httpMethod, $method): void
    {
        if ($httpMethod !== $method) {
           die(throw new MethodNotAllowedException("The page only works with a specific POST method"));
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
        $key = array_key_last(self::$methods);
        if (isset(self::$namedRoutes[$name])) {
            throw new \Exception("This route name already exists : {$name}");
        }
        $root_path = array_key_last(self::$routes[$key]);
        self::$namedRoutes[$name] = [$key, $root_path];
        self::$pageMethod = [$key];
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
        self::$routes[self::getMethods()][$from] = [
            'redirect' => $to,
            'status' => $status,
        ];
    }
    public static function run(string $name, string $param = ''): string
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new \Exception("This route not found: {$name}");
        }

        $path = self::$namedRoutes[$name][1];
        if ($param) {
            $path = explode('/', $path);
            $path = str_replace($path[2], $param, $path);
            $path = implode('/', $path);
        }
        return $path;
    }
    public function middleware(...$middlewares): Route
    {
        $key = array_key_last(self::$routes[self::getMethods()]);
        self::$routes[self::getMethods()][$key]['middleware'] = $middlewares;
        return new self();
    }
}