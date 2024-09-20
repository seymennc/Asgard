<?php

namespace Asgard\system;

use Random\RandomException;

class CSRFToken
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    /**
     * @throws RandomException
     */
    public static function generate(): string
    {
        if (version_compare(PHP_VERSION, '8.2.0') >= 0) {
            $data = bin2hex(random_bytes(32));
        } else {
            if (function_exists('openssl_random_pseudo_bytes')) {
                $data = bin2hex(openssl_random_pseudo_bytes(32));
            } else {
                $data = bin2hex(mt_rand(100000, 999999) . mt_rand(100000, 999999));
            }
        }
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = $data;
        }
        return $_SESSION['_csrf_token'];
    }
}