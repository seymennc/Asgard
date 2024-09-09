<?php

namespace Asgard\database\models;

use Asgard\system\Model;

class Test extends Model
{
    protected $tableName = 'tests';

    protected $fillable = [
        'test_name',
        'test_data',
        'status',
    ];


}