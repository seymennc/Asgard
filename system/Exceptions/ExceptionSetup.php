<?php

namespace Asgard\system\Exceptions;

class ExceptionSetup
{
    /**
     * @return void
     */
    public static function run(): void
    {
        if (env('debug')){
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 0);
            error_reporting(0);
        }

        require_once __DIR__ . '/ErrorHandler.php';
        require_once __DIR__ . '/ExceptionHandler.php';

        set_error_handler(['Asgard\system\Exceptions\ErrorHandler', 'handle']);
        set_exception_handler(['Asgard\system\Exceptions\ExceptionHandler', 'handle']);
    }
}