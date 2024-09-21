<?php

namespace Asgard\system;

class Seeder
{
    /**
     * @param array $seeder
     * @return void
     */
    public function start(array $seeder): void
    {
        foreach ($seeder as $seed) {
            $seed = new $seed();
            $seed->run();
        }
    }
}