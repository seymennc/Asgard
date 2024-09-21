<?php

namespace Asgard\app;

use Asgard\App\Helpers\Path;
use Asgard\System\Exceptions\ExceptionSetup;
use Asgard\system\Exceptions\Method\InvalidArgumentException;
use Asgard\System\Route;
use Dotenv\Dotenv;

class bootstrap
{

    /**
     * @throws InvalidArgumentException
     */
    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(dirname(__DIR__));
        $dotenv->load();

        $this->routeStructure();

        Path::setBasePath(dirname(__DIR__));
        ExceptionSetup::run();
    }

    /**
     * @return void
     * @throws \Exception
     */
    private function routeStructure(): void
    {
        require dirname(__DIR__) . '/routes/web.php';
        require dirname(__DIR__) . '/routes/api.php';
        Route::dispatch();
    }
}