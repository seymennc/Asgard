<?php

namespace Asgard\config;

class Config
{
    /**
     * @param $key
     * @return mixed|null
     */
    public static function getBladeData($key): mixed
    {
        $config = include __DIR__."/blade.php";

        return $config[$key] ?? null;
    }
}