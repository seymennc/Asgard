<?php

namespace Asgard\system;

class Seeder
{
    public function start(array $seeder): void
    {
        foreach ($seeder as $seed) {
            $seed = new $seed();
            $seed->run();
        }
    }
}