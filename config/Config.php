<?php

namespace Asgard\config;

class Config
{
    public static function getAppData($key): mixed
    {
        $config = include __DIR__ . "/app.php";

        return $config[$key] ?? null;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public static function getBladeData($key): mixed
    {
        $config = include __DIR__ . "/blade.php";

        return $config[$key] ?? null;
    }
}