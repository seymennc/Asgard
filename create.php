<?php

require_once __DIR__ . '/vendor/autoload.php';
use Asgard\app\Console\Console;

$console = new Console();
$console->run($argv);