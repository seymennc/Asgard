<?php

namespace Asgard\app\Console\Command;

use Asgard\system\Migration;

class Migrate
{
    public function handle(): void
    {
        $migrate = new Migration();
        $migrate->migrate();
    }
}