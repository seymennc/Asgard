<?php

use Asgard\App\Helpers\Redirect;
use Asgard\system\Blade;
use Asgard\System\Route;
use Dotenv\Dotenv;

/**
 * @param string $name
 * @param array $params
 * @return string
 */
function view(string $name, array $params = []): string
{
    $blade = new Blade();

    return $blade->view($name, $params);
    //return View::show($name, $params);
}

function route(string $name, string $params = ''): string
{
    return Route::run($name, $params);
}
function redirect(string $name, string $params = ''): void
{
    Redirect::to(route($name, $params));
}

function env($key, $default = null)
{
    $dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__) . '/../');
    $dotenv->load();

    return getenv($key) ?? $default;
}

