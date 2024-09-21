<?php

namespace Asgard\system\Route;

use Asgard\App\Helpers\Redirect;
use Asgard\app\Kernel;
use Asgard\app\Middlewares\middlewares;
use Asgard\system\Exceptions\Method\MethodNotAllowedException;
use Asgard\system\Exceptions\Method\RequestException;
use Asgard\system\Request;

class Handle
{
    /**
     * @param $val
     * @param $url
     * @param $method
     * @param $params
     * @return void
     * @throws MethodNotAllowedException
     * @throws \ReflectionException
     */
    public static function handleMiddleware($val, $url, $method, $params): void
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


    /**
     * @param $val
     * @param $params
     * @return void
     * @throws \ReflectionException
     * @throws \Exception
     */
    private static function handleRoute($val, $params): void
    {
        if (isset($val['redirect'])) {
            Redirect::to($val['redirect'], $val['status']);
        } else {
            self::invokeCallback($val['callback'], $params);
        }
    }

    /**
     * @param $path
     * @return string
     */
    public static function preparePattern($path): string
    {
        foreach (Route::$patterns as $key => $pattern) {
            $path = preg_replace('#' . $key . '#', $pattern, $path);
        }
        return '#^' . rtrim($path, '/') . '/?$#';
    }

    /**
     * @param $callback
     * @param $params
     * @return void
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

    /**
     * @param $method
     * @param $path
     * @param $callback
     * @return Route
     * @throws RequestException
     */
    public static function addRoute($method, $path, $callback): Route
    {
        $formattedPath = '/' . ltrim(Route::$prefix . $path, '/');
        if (isset(Route::$routes[$method][$formattedPath])) {
            throw new RequestException("This route already exists: {$formattedPath}");
        }
        Route::$routes[$method][$formattedPath] = ['callback' => $callback];
        return new Route();
    }
}