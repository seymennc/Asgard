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
    public static function createTable(string $tableName, callable $callback): void
    {
        $instance = new static();
        $instance->tableName = $tableName;
        $sql = "CREATE TABLE IF NOT EXISTS $tableName (id INT AUTO_INCREMENT PRIMARY KEY";

        $callback($instance);

        // Eklenen sütunları da SQL sorgusuna ekle
        foreach ($instance->columns as $column) {
            $sql .= ", " . $column;
        }

        $sql .= ");";

        $instance->execute($sql);
    }

    public static function dropTable(string $tableName): void
    {
        $instance = new static();
        $sql = "DROP TABLE IF EXISTS $tableName;";
        $instance->execute($sql);
    }

    public function addColumn(string $columnName, string $type, array $options = []): void
    {
        $sqlOptions = [];
        if (!empty($options)) {
            foreach ($options as $option) {
                $sqlOptions[] = $option;
            }
        }

        // Kolon tanımını oluştur
        $columnDefinition = "$columnName $type";
        if (!empty($sqlOptions)) {
            $columnDefinition .= ' ' . implode(' ', $sqlOptions);
        }

        // Kolonu ekleme için listeye ekle
        $this->columns[] = $columnDefinition;
    }

    public function dropColumn(string $columnName): void
    {
        $sql = "ALTER TABLE $this->tableName DROP COLUMN $columnName;";
        $this->execute($sql);
    }

    protected function execute(string $sql): void
    {
        $exec = $this->db->prepare($sql);
        $exec->execute();
    }
}