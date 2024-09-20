<?php

namespace Asgard\system\Exceptions\Method;

class CSRFTokenException extends \Exception
{
    protected $message = 'Invalid CSRF token';
    protected $statusCode = 403;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}