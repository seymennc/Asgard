<?php

namespace Asgard\database\seeders;

use Asgard\system\Seeder;

class DatabaseSeeder extends Seeder
{

    protected array $seeders = [
        DenemeSeeder::class,
        TestSeeder::class,
    ];
    public function run(): void
    {
        $this->start($this->seeders);
    }

    public function getSeederClasses(): array
    {
        return $this->seeders;
    }
}