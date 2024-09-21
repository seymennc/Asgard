<?php

namespace Asgard\system\Blueprint;

class Column
{
    /**
     * @param string $columnName
     * @param string $type
     * @param array $options
     * @return void
     */
    public static function addColumn(string $columnName, string $type, array $options = []): void
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

        Blueprint::$columns[] = $columnDefinition;
    }

    /**
     * @param string $columnName
     * @return void
     */
    public static function dropColumn(string $columnName): void
    {
        $instance = new Blueprint();
        $tableName = $instance::$tableName;
        $sql = "ALTER TABLE $tableName DROP COLUMN $columnName;";
        $instance->execute($sql);
    }
}