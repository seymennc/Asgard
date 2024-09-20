<?php

namespace Asgard\system\Exceptions\Method;

class PageNotFoundException extends \Exception
{
    protected $message = 'Page not found';
    protected $statusCode = 404;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}