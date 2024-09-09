<?php

namespace Asgard\app\Middlewares;

use Asgard\app\Kernel;

class middlewares extends Kernel
{
    protected static array $middleware = [
        'deneme' => \Asgard\app\Middlewares\DenemeMiddleware::class,
        'ok'    => \Asgard\app\Middlewares\OkMiddleware::class,
    ];
}