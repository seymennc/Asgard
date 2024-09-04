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
        header('Location: ' . getenv('BASE_PATH') . $url, true, $status);
    }
}