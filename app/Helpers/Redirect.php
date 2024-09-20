<?php

namespace Asgard\App\Helpers;

class Redirect
{
    /**
     * @param string $url
     * @param int $status
     * @return void
     */
    public static function to(string $url, int $status = 301): void
    {
        header('Location: ' . route($url), true, $status);
    }
    public static function back(): void
    {
        $previousUrl = $_SERVER['HTTP_REFERER'] ?? '/';

        var_dump($previousUrl);
    }
}