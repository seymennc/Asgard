<?php

namespace Asgard\system;

class Database
{


    public \PDO $db;
    public static string $table;
    public array $where = [];
    protected string $sql = '';

    protected $tableName = null;

    public function __construct()
    {
        $this->db = new \PDO(sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', env('DB_HOST'), env('DB_PORT'), env('DB_NAME'), env('DB_CHARSET')), env('DB_USERNAME'), env('DB_PASSWORD'));
    }

    public static function table(string $table): Database
    {
        self::$table = $table;
        return new self();
    }

/** The code in this field is for ModelName::where('status', 1)->get(); */
    /**
     * @param string $column
     * @param $value
     * @param string $operator
     * @return static
     */
    public static function where(string $column, $value, string $operator = '='): static
    {
        $instance = new static();
        $instance->where[] = $column . ' ' . $operator . ' "' . $value . '"';
        return $instance;
    }

    public static function insert(array $data): void
    {
        $instance = new static();
        $instance->sql = sprintf('INSERT INTO %s (%s) VALUES (%s)', $instance->getTableName(), implode(', ', array_keys($data)), ':' . implode(', :', array_keys($data)));

        $query = $instance->db->prepare($instance->sql);

        foreach ($data as $key => $value) {
            $query->bindValue(':' . $key, $value);
        }
        $query->execute();
    }

    protected function getTableName(): string
    {
        $array = explode('\\', static::class);
        return $this->tableName ?? sprintf(strtolower(end($array)) . '%s', 's');
    }
    
    protected function prepareSql(): void
    {
        $this->sql = sprintf('SELECT * FROM %s', $this->getTableName());
        if (count($this->where)) {
            $this->sql .= ' WHERE ' . implode(' AND ', $this->where);
        }
    }
    public function get(): false|array
    {
        $this->prepareSql();
        $query = $this->db->prepare($this->sql);
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_OBJ);
    }

    public function first()
    {
        $this->prepareSql();
        $query = $this->db->prepare($this->sql);
        $query->execute();
        return $query->fetch(\PDO::FETCH_OBJ);
    }
}