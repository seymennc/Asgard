<?php

namespace Asgard\app\Middlewares;

use Closure;

class OkMiddleware
{
    public function handle($request, Closure $next)
    {
        echo 'Ok Middleware çalıştı';
        return $next($request);
    }
}