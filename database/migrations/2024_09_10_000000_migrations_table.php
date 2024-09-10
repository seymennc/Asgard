<?php

namespace Asgard\database\migrations;

use Asgard\system\Blueprint;
use Asgard\system\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Blueprint::createTable('migrations', function (Blueprint $table) {
            $table->addColumn('table_name', 'VARCHAR(255)', ['NOT NULL']);
            $table->addColumn('created_at', 'TIMESTAMP', ['DEFAULT CURRENT_TIMESTAMP']);
            $table->addColumn('updated_at', 'TIMESTAMP', ['DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP']);
        });
    }

    public function down(): void
    {
        Blueprint::dropTable('migrations');
    }
};
