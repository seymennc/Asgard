<?php

namespace Asgard\app;

use Asgard\app\Helpers\Path;
use Asgard\system\Route;
use Dotenv\Dotenv;

class bootstrap
{

    /**
     * @throws \ReflectionException
     */
    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
        $dotenv->load();

        require dirname(__DIR__) . '/routes/web.php';
        require dirname(__DIR__) . '/routes/api.php';
        Route::dispatch();

        Path::setBasePath(dirname(__DIR__));

    }
}