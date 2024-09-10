<?php

namespace Asgard\app\Console\Command;

use Asgard\system\Migration;

class Migrate
{
    public function handle()
    {
        $migrate = new Migration();
        $migrate->migrate();
    }
}