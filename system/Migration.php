<?php

namespace Asgard\system;

use Asgard\database\models\Migrations;

class Migration
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
                $migration = require $file; // Anonim sınıfı döndüren dosyayı dahil et
                $className = sprintf(basename($file, '%s'), '.php');

                $spaceMigration = str_repeat(' ', strlen($className) - 28);
                $spaceMigrated = str_repeat(' ', strlen($className) - 27);


                if (is_object($migration) && method_exists($migration, 'up')) {
                    if (empty(Migrations::where('table_name', $className)->first()->table_name)){
                        echo $this->formatWarningMessage("Migrating:") . $spaceMigration .$className . "\n";

                        $migration->up();

                        Migrations::insert([
                            'table_name' => $className,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        $this->message = $this->formatSuccessMessage("Migrated:") . $spaceMigrated .$className . "\n";
                    }
                } else {
                    echo "Migration class not found or does not have 'up' method in file: $file\n";
                }
            }
            echo $this->message ?? PHP_EOL . $this->formatSuccessMessage('Nothing to migrate!') . PHP_EOL . PHP_EOL;
        } else {
            echo PHP_EOL . $this->formatWarningMessage('Migration file doesn\'t exists!') . PHP_EOL. PHP_EOL;
        }
    }

    public function rollback(): void
    {
        // Rollback işlemleri
    }

    private function checkAndCreateMigrationsTable(): void
    {
        // Tablo var mı kontrolü
        $query = $this->db->query("SHOW TABLES LIKE 'migrations'");
        if ($query->rowCount() === 0) {
            // Tablo mevcut değilse oluştur
            $migration = require dirname(__DIR__) . '/database/migrations/2024_09_10_000000_migrations_table.php';
            $migration->up();
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
