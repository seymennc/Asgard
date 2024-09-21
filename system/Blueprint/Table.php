<?php

namespace Asgard\system\Blueprint;

class Table
{
    /**
     * @param string $tableName
     * @param callable $callback
     * @return void
     */
    public static function createTable(string $tableName, callable $callback): void
    {
        $instance = new Blueprint();
        $instance::$tableName = $tableName;
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (id INT AUTO_INCREMENT PRIMARY KEY";

        $callback($instance);

        foreach ($instance::$columns as $column) {
            $sql .= ", " . $column;
        }

        $sql .= ");";

        $instance->execute($sql);
    }

    /**
     * @param string $tableName
     * @return void
     */
    public static function dropTable(string $tableName): void
    {
        $instance = new Blueprint();
        $sql = "DROP TABLE IF EXISTS $tableName;";
        $instance->execute($sql);
    }

}