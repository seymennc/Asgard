<?php

namespace Asgard\system\Exceptions\Method;

class InvalidArgumentException extends \Exception
{
    protected $message;
    protected $code = 500;
}