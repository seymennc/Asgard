<?php

namespace Asgard\app\Console\Command;

use Asgard\database\seeders\DatabaseSeeder;

class Seed
{
    public function handle(): void
    {
        echo "Running seeders...\n";

        $seeder = new DatabaseSeeder();
        $this->runSeeders($seeder);
    }

    /**
     * @param DatabaseSeeder $seeder
     * @return void
     */
    private function runSeeders(DatabaseSeeder $seeder): void
    {
        $seederClasses = $seeder->getSeederClasses();

        foreach ($seederClasses as $seederClass) {
            $this->runSeeder($seederClass);
        }
        echo PHP_EOL . PHP_EOL . "\tAll seeders are done." . PHP_EOL . PHP_EOL . PHP_EOL;
    }

    /**
     * @param string $seederClass
     * @return void
     */
    private function runSeeder(string $seederClass): void
    {
        try {
            $seederInstance = new $seederClass();
            $seederInstance->run();
            $this->outputResults($seederClass, true);

        }catch (\Exception $e) {
            $this->outputResults($seederClass, false, $e->getMessage());
        }

    }

    /**
     * @param string $seederClass
     * @param bool $status
     * @param string $message
     * @return void
     */
    private function outputResults(string $seederClass, bool $status, string $message = ''): void
    {
        $dots = str_repeat('.', 80 - strlen($seederClass));
        $statusText = $status ? $this->formatSuccess() : $this->formatFail();

        echo "> {$this->formatClassName($seederClass)} {$dots} {$statusText} {$message}" . PHP_EOL;
    }

    /**
     * @param string $className
     * @return string
     */
    protected function formatClassName(string $className): string
    {
        $parts = explode('\\', $className);
        $result = array_pop($parts);

        $namespace = implode('\\', $parts);

        return ($namespace ? $namespace . '\\' : '') . "\033[38;5;214m" . $result . "\033[0m";
    }

    /**
     * @return string
     */
    protected function formatSuccess(): string
    {
        return "\033[42;97m OK! \033[0m";
    }

    /**
     * @return string
     */
    protected function formatFail(): string
    {
        return "\033[41;97m FAIL! \033[0m";
    }

}