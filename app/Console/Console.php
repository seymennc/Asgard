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

    /**
     * @return void
     */
    protected function loadCommands(): void
    {
        $commandDir = __DIR__ . '/Command';


        foreach (glob("{$commandDir}/*.php") as $file) {
            $className = 'Asgard\\App\\Console\\Command\\' . pathinfo($file, PATHINFO_FILENAME);

            if (class_exists($className)) {
                $reflection = new ReflectionClass($className);
                $commandName = strtolower(str_replace('Command', '', $reflection->getShortName()));
                $this->commands[$commandName] = $className;
            }
        }
    }

    /**
     * @param $argv
     * @return void
     */
    public function run($argv): void
    {
        $input = $argv[1] ?? null;
        if (!$input) {
            $this->listCommands();
            exit(1);
        }

        [$commandName, $subCommand] = explode(':', $input) + [null, null];

        if (!array_key_exists($commandName, $this->commands)) {
            $this->listCommands();
            exit(1);
        }

        $commandClass = $this->commands[$commandName];
        $commandInstance = new $commandClass();

        if ($subCommand != null && method_exists($commandInstance, $subCommand)) {
            call_user_func([$commandInstance, $subCommand], array_slice($argv, 2));
        } else {
            $commandInstance->handle();
        }
    }

    /**
     * @return void
     */
    protected function listCommands(): void
    {
        echo "Geçersiz komut. Kullanılabilir komutlar:\n";
        foreach (array_keys($this->commands) as $availableCommand) {
            echo " - " . $availableCommand . "\n";
        }
    }
}