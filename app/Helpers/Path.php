<?php

namespace Asgard\app\Helpers;

use Asgard\config\Config;
use Asgard\system\Exceptions\Method\InvalidArgumentException;

class Path
{
    private static string $basePath;

    /**
     * @param $path
     * @return void
     * @throws InvalidArgumentException
     */
    public static function setBasePath($path): void
    {
        if(!is_dir($path)){
            throw new InvalidArgumentException($path . " is not a directory");
        }
        self::$basePath = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public static function getBasePath(): string
    {
        if(!self::$basePath){
            throw new InvalidArgumentException("Base path is not set");
        }
        return self::$basePath;
    }
}