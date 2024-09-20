<?php

namespace Asgard\app;

use Asgard\App\Middlewares\middlewares;

class Kernel
{
    public static function getMiddlewareClass(string $name): string
    {
        if (!isset(middlewares::$middleware[$name])) {
            throw new \Exception("Middleware bulunamadı: {$name}");
        }
        return middlewares::$middleware[$name];
    }
    public static function getDefaultMiddleware(): array
    {
        return middlewares::$default;
    }
}