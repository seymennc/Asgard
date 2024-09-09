<?php

namespace Asgard\database\seeders;

use Asgard\database\models\Test;
use Luminance\system\Seeder;
class TestSeeder
{
    public function run(): void
    {
        Test::insert([
            'test_name' => 'deneme',
            'test_data' => 'deneme',
            'status' => 1,
        ]);
    }
}