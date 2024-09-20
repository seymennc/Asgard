<?php

namespace Asgard\system\Exceptions\Method;

class CSRFTokenException extends \Exception
{
    protected $message = 'Invalid CSRF token';
    protected $code = 403;
}