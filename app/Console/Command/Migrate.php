<?php

namespace Asgard\app\Console\Command;

use Asgard\system\Migration\Migration;

class Migrate
{
    public function handle(): void
    {
        try {
            $migrate = new Migration();
            $migrate->migrate();
        }catch (\Exception $e){
            $exp = explode(' ', $e->getMessage());
            if ($exp[1] === '[1049]') {
                Migration::createDatabase();
            }
        }
    }
}