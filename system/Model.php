<?php

namespace Asgard\system;

use Asgard\system\Database\Database;

abstract class Model extends Database
{
    protected $tableName;

    public function __construct()
    {
        parent::__construct();
    }
}