<?php

namespace Asgard\system\Exceptions\Method;

class MethodNotAllowedException extends \Exception
{
    protected $message;
    protected int $statusCode = 405;

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}