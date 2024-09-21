<?php

namespace Asgard\system\Migration;

class Methods
{
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

                Migration::migrate();
                break;
            case 'N':
                echo "Database not created!" . PHP_EOL;
                break;
            default:
                echo "Invalid input!" . PHP_EOL;
                break;
        }

    }

    public static function checkAndCreateMigrationsTable(): void
    {
        $instance = new Migration();
        $query = $instance->db->query("SHOW TABLES LIKE 'migrations'");
        if ($query->rowCount() === 0) {
            $migration = require dirname(__DIR__, 2) . '/database/migrations/2024_09_10_000000_migrations_table.php';
            $migration->run();
        }
    }
}