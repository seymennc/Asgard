<?php

namespace Asgard\config;

class Config
{
    /**
     * @param string $key
     * @return string
     */
    public static function get(string $key): string
    {
        $key = explode('.', $key);
        $config = include "{$key[0]}.php";

        return $config[$key[1]] ?? "empty";
    }
}