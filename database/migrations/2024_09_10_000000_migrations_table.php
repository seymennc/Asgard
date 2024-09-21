<?php

namespace Asgard\database\migrations;

use Asgard\system\Blueprint\Blueprint;
use Asgard\system\Migration\Migration;

return new class extends Migration
{
    public function run(): void
    {
        Blueprint::createTable('migrations', function (Blueprint $table) {
            $table->addColumn('table_name', 'VARCHAR(255)', ['NOT NULL']);
            $table->addColumn('created_at', 'TIMESTAMP', ['DEFAULT CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'TIMESTAMP', ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        });
    }

    public function stop(): void
    {
        Blueprint::dropTable('migrations');
    }
};
