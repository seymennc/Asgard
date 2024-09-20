<?php

namespace Asgard\system\Exceptions\Method;

class RequestException extends \Exception
{
    protected $message;
    protected $statusCode = 400;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}