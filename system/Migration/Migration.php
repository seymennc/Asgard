<?php

namespace Asgard\system\Migration;

use AllowDynamicProperties;
use Asgard\database\models\Migrations;
use Asgard\system\Database\Database;

#[AllowDynamicProperties] class Migration
{

    private static ?string $message;

    public function __construct()
    {
        $db = new Database();
        $this->db = $db->db;
    }

    public static function migrate(): void
    {
        $files = glob(dirname(__DIR__, 2) . '/database/migrations/*.php');

        Methods::checkAndCreateMigrationsTable();

        if (!empty($files)) {
            foreach ($files as $file) {
                $migration = require $file;
                $className = sprintf(basename($file, '%s'), '.php');

                $spaceMigration = str_repeat(' ', strlen($className) - 28);
                $spaceMigrated = str_repeat(' ', strlen($className) - 27);


                if (is_object($migration) && method_exists($migration, 'run')) {
                    if (empty(Migrations::where('table_name', $className)->first()->table_name)) {
                        echo Format::formatWarningMessage("Migrating:") . $spaceMigration . $className . "\n";

                        $migration->run();

                        Migrations::insert([
                            'table_name' => $className,
                            'created_at' => date('Y-m-d H:i:s')
                        ]);

                        self::$message = Format::formatSuccessMessage("Migrated:") . $spaceMigrated . $className . "\n";
                    }
                } else {
                    echo "Migration class not found or does not have 'run' method in file: $file\n";
                }
            }
            echo self::$message ?? PHP_EOL . Format::formatSuccessMessage('Nothing to migrate!') . PHP_EOL . PHP_EOL;
        } else {
            echo PHP_EOL . Format::formatWarningMessage('Migration file doesn\'t exists!') . PHP_EOL . PHP_EOL;
        }
    }

    public function rollback(): void
    {
        // Rollback operations added soon
    }

    public static function createDatabase(): void
    {
        Methods::createDatabase();
    }
}
