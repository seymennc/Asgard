<?php

namespace Asgard\app\Helpers;

use Asgard\config\Config;
use http\Exception\InvalidArgumentException;

class Path
{
    private static string $basePath;

    /**
     * @param $path
     * @return void
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
     */
    public static function getBasePath(): string
    {
        if(!self::$basePath){
            throw new InvalidArgumentException("Base path is not set");
        }
        return self::$basePath;
    }
}