<?php

namespace Asgard\app;

use Asgard\app\Middlewares\middlewares;

class Kernel
{
    public static function getMiddlewareClass(string $name): string
    {
        if (!isset(middlewares::$middleware[$name])) {
            throw new \Exception("Middleware bulunamadı: {$name}");
        }
        return middlewares::$middleware[$name];
    }
}