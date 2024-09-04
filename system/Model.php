<?php

namespace Asgard\system;

abstract class Model extends Database
{
    protected $tableName;

    public function __construct()
    {
        parent::__construct();
    }
}