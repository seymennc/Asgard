<?php

namespace Asgard\app\Console;

use Asgard\app\Helpers\Path;
use Asgard\config\Config;
use Dotenv\Dotenv;
use ReflectionClass;

require_once dirname(__DIR__) . '/../vendor/autoload.php';

class Console
{
    protected array $commands = [];

    public function __construct()
    {
        $this->loadCommands();
    }
    protected function loadCommands(): void
    {
        $commandDir = __DIR__ . '/Command';


        foreach (glob("{$commandDir}/*.php") as $file) {
            $className = Config::getAppData('base_path') . '\\App\\Console\\Command\\' . pathinfo($file, PATHINFO_FILENAME);

            if (class_exists($className)) {
                $reflection = new ReflectionClass($className);
                $commandName = strtolower(str_replace('Command', '', $reflection->getShortName()));
                $this->commands[$commandName] = $className;
            }
        }
    }
    public function run($argv): void
    {
        $commandName = $argv[1] ?? null;

        if (!$commandName || !array_key_exists($commandName, $this->commands)) {
            $this->listCommands();
            exit(1);
        }

        $commandClass = $this->commands[$commandName];
        $commandInstance = new $commandClass();
        $commandInstance->handle();
    }

    protected function listCommands()
    {
        echo "Geçersiz komut. Kullanılabilir komutlar:\n";
        foreach (array_keys($this->commands) as $availableCommand) {
            echo " - " . $availableCommand . "\n";
        }
    }
}