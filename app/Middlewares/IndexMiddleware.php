<?php

namespace App\Middlewares;

use Asgard\system\Request;

class IndexMiddleware
{
    public function handle(Request $request, \Closure $next): void
    {
        echo "Index Middleware";
    }
}