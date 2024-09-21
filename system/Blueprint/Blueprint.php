<?php

namespace Asgard\system\Blueprint;

use Asgard\system\Database\Database;

class Blueprint
{
    protected \PDO $db;
    public static string $tableName;
    public static array $columns;

    public function __construct()
    {
        $db = new Database();
        $this->db = $db->db;
    }

    /**
     * @param string $tableName
     * @param callable $callback
     * @return void
     */
    public static function createTable(string $tableName, callable $callback): void
    {
        Table::createTable($tableName, $callback);
    }

    /**
     * @param string $tableName
     * @return void
     */
    public static function dropTable(string $tableName): void
    {
        Table::dropTable($tableName);
    }

    /**
     * @param string $columnName
     * @param string $type
     * @param array $options
     * @return void
     */
    public static function addColumn(string $columnName, string $type, array $options = []): void
    {
        Column::addColumn($columnName, $type, $options);
    }

    /**
     * @param string $columnName
     * @return void
     */
    public static function dropColumn(string $columnName): void
    {
        Column::dropColumn($columnName);
    }

    /**
     * @param string $sql
     * @return void
     */
    public static function execute(string $sql): void
    {
        $instance = new static();
        $exec = $instance->db->prepare($sql);
        $exec->execute();
    }
}