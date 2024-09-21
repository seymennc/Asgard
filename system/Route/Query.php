<?php

namespace Asgard\system\Route;

use Asgard\system\Exceptions\Method\MethodNotAllowedException;
use Asgard\system\Exceptions\Method\PageNotFoundException;

class Query
{

    /**
     * @return void
     * @throws PageNotFoundException
     */
    public static function hasRoute(): void
    {
        if (!Route::$hasRoute) {
            die(throw new PageNotFoundException());
        }
    }

    /**
     * @param $httpMethod
     * @param $method
     * @return void
     * @throws MethodNotAllowedException
     */
    public static function hasPost($httpMethod, $method): void
    {
        if ($httpMethod !== $method) {
            die(throw new MethodNotAllowedException("The page only works with a specific POST method"));
        }

    }

    /**
     * @return string
     */
    public static function getMethods(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    /**
     * @return array|string
     */
    public static function getUrl(): array|string
    {
        return str_replace(getenv("BASE_PATH"), '', $_SERVER['REQUEST_URI']);
    }

}