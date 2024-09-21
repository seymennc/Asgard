<?php

namespace Asgard\system\Route;


use Asgard\App\Helpers\Redirect;
use Asgard\app\Kernel;
use Asgard\app\Middlewares\middlewares;
use Asgard\system\Exceptions\Method\MethodNotAllowedException;
use Asgard\system\Exceptions\Method\PageNotFoundException;
use Asgard\system\Exceptions\Method\RequestException;
use Asgard\system\Request;

class Route extends Method
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
    protected static array $namedRoutes = [];
    public static array $methods = [];
    protected static array $pageMethod = [];

    /**
     * @return void
     * @throws MethodNotAllowedException
     * @throws PageNotFoundException
     * @throws \Exception
     */
    public static function dispatch(): void
    {
        $url = Query::getUrl();
        $method = Query::getMethods();

        foreach (self::$routes as $httpMethod => $routes) {
            foreach ($routes as $path => $val) {
                $pattern = Handle::preparePattern($path);

                if (preg_match($pattern, $url, $params)) {
                    self::$hasRoute = true;
                    Query::hasPost($httpMethod, $method);

                    array_shift($params);
                    Handle::handleMiddleware($val, $url, $method, $params);

                    return;
                }
            }
        }
        Query::hasRoute();
    }
    /**
     * @param string $name
     * @param string $param
     * @return string
     * @throws RequestException
     */
    public static function run(string $name, string $param = ''): string
    {
        if (!isset(self::$namedRoutes[$name])) {
            throw new RequestException("This route not found: {$name}");
        }

        $path = self::$namedRoutes[$name][1];
        if ($param) {
            $path = explode('/', $path);
            $path = str_replace($path[2], $param, $path);
            $path = implode('/', $path);
        }
        return $path;
    }

}