<?php

namespace Asgard\app\Middlewares;

use Asgard\App\Helpers\Redirect;
use Closure;

class DenemeMiddleware
{
    public function handle($request, Closure $next)
    {
         Redirect::back();
    }
}