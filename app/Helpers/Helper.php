<?php

use Asgard\App\Helpers\Redirect;
use Asgard\config\Config;
use Asgard\system\Blade;
use Asgard\system\Exceptions\Method\PageNotFoundException;
use Asgard\System\Route;
use Dotenv\Dotenv;

/**
 * @param string $name
 * @param array $params
 * @return string
 * @throws PageNotFoundException
 */
function view(string $name, array $params = []): string
{
    $blade = new Blade();
    return $blade->view($name, $params);
}

/**
 * @param string $name
 * @param string $params
 * @return string
 * @throws Exception
 */
function route(string $name, string $params = ''): string
{
    return Route::run($name, $params);
}

/**
 * @param string $name
 * @param string $params
 * @return void
 * @throws Exception
 */
function redirect(string $name, string $params = ''): void
{
    Redirect::to(route($name, $params));
}

/**
 * @param $key
 * @param $default
 * @return array|false|mixed|string|null
 */
function env($key, $default = null): mixed
{
    $dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__) . '/../');
    $dotenv->load();

    return getenv($key) ? getenv($key) : $default;
}
function public_path($path): string
{
    return "/{$path}";
}

function config(string $key): string
{
    return Config::get($key);
}