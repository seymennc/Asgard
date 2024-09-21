<?php

namespace App\Middlewares;

use Asgard\system\Exceptions\Method\CSRFTokenException;
use Asgard\system\Request;

class CSRFToken
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     * @throws CSRFTokenException
     */
    public function handle(Request $request, \Closure $next)
    {
        if ($request->getMethod() === 'post') {
            if (!$request->_csrf_token) {
                die(throw new CSRFTokenException("CSRF token is missing"));
            }
            $this->verify_csrf_token($request->_csrf_token);
        }
        return $next($request);
    }

    /**
     * @param $token
     * @return void
     * @throws CSRFTokenException
     */
    function verify_csrf_token($token): void
    {
        if (!isset($_SESSION['_csrf_token']) || $token !== $_SESSION['_csrf_token']) {
            throw new CSRFTokenException("Invalid CSRF token");
        }
    }

}