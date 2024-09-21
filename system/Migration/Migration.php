<?php

namespace Asgard\system;

use AllowDynamicProperties;
use Asgard\database\models\Migrations;

#[AllowDynamicProperties] class Migration
{

    protected ?string $message = null;

    public function __construct()
    {
        $db = new Database();
        $this->db = $db->db;
    }

    public function migrate(): void
    {
        $files = glob(__DIR__ . '/../database/migrations/*.php');

        $this->checkAndCreateMigrationsTable();

        if (!empty($files)) {
            foreach ($files as $file) {
                $migration = require $file;
                $className = sprintf(basename($file, '%s'), '.php');

                $spaceMigration = str_repeat(' ', strlen($className) - 28);
                $spaceMigrated = str_repeat(' ', strlen($className) - 27);


                if (is_object($migration) && method_exists($migration, 'run')) {
                    if (empty(Migrations::where('table_name', $className)->first()->table_name)) {
                        echo $this->formatWarningMessage("Migrating:") . $spaceMigration . $className . "\n";

                        $migration->run();

                        Migrations::insert([
                            'table_name' => $className,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        $this->message = $this->formatSuccessMessage("Migrated:") . $spaceMigrated . $className . "\n";
                    }
                } else {
                    echo "Migration class not found or does not have 'run' method in file: $file\n";
                }
            }
            echo $this->message ?? PHP_EOL . $this->formatSuccessMessage('Nothing to migrate!') . PHP_EOL . PHP_EOL;
        } else {
            echo PHP_EOL . $this->formatWarningMessage('Migration file doesn\'t exists!') . PHP_EOL . PHP_EOL;
        }
    }

    public function rollback(): void
    {
        // Rollback operations added soon
    }

    public static function createDatabase(): void
    {
        echo "Database not found. Do you want to create it? (Y/N): ";
        $handle = fopen("php://stdin", "r");
        $line = trim(fgets($handle));
        switch ($line) {
            case 'Y':
            case 'y':
            case 'YE':
            case 'ye':
            case 'yes':
            case 'YES':
                $pdo = new \PDO("mysql:host=" . env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));

                $sql = "CREATE DATABASE IF NOT EXISTS " . env('DB_NAME');
                $pdo->exec($sql);
                echo "Database created successfully!" . PHP_EOL;

                $instance = new self();
                $instance->migrate();
                break;
            case 'N':
                echo "Database not created!" . PHP_EOL;
                break;
            default:
                echo "Invalid input!" . PHP_EOL;
                break;
        }

    }

    private function checkAndCreateMigrationsTable(): void
    {
        $query = $this->db->query("SHOW TABLES LIKE 'migrations'");
        if ($query->rowCount() === 0) {
            $migration = require dirname(__DIR__) . '/database/migrations/2024_09_10_000000_migrations_table.php';
            $migration->run();
        }
    }

    private function formatSuccessMessage(string $message): string
    {
        return "\e[32m$message\e[0m";
    }

    private function formatWarningMessage(string $message): string
    {
        return "\e[33m$message\e[0m";
    }
}
