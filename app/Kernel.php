<?php

namespace Asgard\app;

use Asgard\App\Middlewares\middlewares;
use Asgard\system\Exceptions\Method\MethodNotAllowedException;

class Kernel
{
    /**
     * @throws MethodNotAllowedException
     */
    public static function getMiddlewareClass(string $name): string
    {
        if (!isset(middlewares::$middleware[$name])) {
            throw new MethodNotAllowedException("Middleware bulunamadı: {$name}");
        }
        return middlewares::$middleware[$name];
    }
    public static function getDefaultMiddleware(): array
    {
        return middlewares::$default;
    }
}