<?php

namespace Asgard\app\Middlewares;

use Asgard\app\Kernel;

class middlewares extends Kernel
{
    protected static array $default = [
        'csrf'
    ];
    protected static array $middleware = [
        'csrf' => \App\Middlewares\CSRFToken::class,
        'index' => \App\Middlewares\IndexMiddleware::class
    ];
}