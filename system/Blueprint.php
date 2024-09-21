<?php

namespace Asgard\system;

class Blueprint
{
    protected \PDO $db;
    protected string $tableName;
    protected array $columns = [];

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
        $instance = new static();
        $instance->tableName = $tableName;
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (id INT AUTO_INCREMENT PRIMARY KEY";

        $callback($instance);

        foreach ($instance->columns as $column) {
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
        $instance = new static();
        $sql = "DROP TABLE IF EXISTS $tableName;";
        $instance->execute($sql);
    }

    /**
     * @param string $columnName
     * @param string $type
     * @param array $options
     * @return void
     */
    public function addColumn(string $columnName, string $type, array $options = []): void
    {
        $sqlOptions = [];
        if (!empty($options)) {
            foreach ($options as $option) {
                $sqlOptions[] = $option;
            }
        }

        $columnDefinition = "$columnName $type";
        if (!empty($sqlOptions)) {
            $columnDefinition .= ' ' . implode(' ', $sqlOptions);
        }

        $this->columns[] = $columnDefinition;
    }

    /**
     * @param string $columnName
     * @return void
     */
    public function dropColumn(string $columnName): void
    {
        $sql = "ALTER TABLE $this->tableName DROP COLUMN $columnName;";
        $this->execute($sql);
    }

    /**
     * @param string $sql
     * @return void
     */
    protected function execute(string $sql): void
    {
        $exec = $this->db->prepare($sql);
        $exec->execute();
    }
}