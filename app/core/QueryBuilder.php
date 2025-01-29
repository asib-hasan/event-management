<?php

class QueryBuilder {
    private $pdo;
    private $table;
    private $fields = "*";
    private $conditions = [];
    private $params = [];

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function table($table) {
        $this->table = $table;
        return $this;
    }

    public function select($fields = "*") {
        $this->fields = $fields;
        return $this;
    }

    public function where($field, $operator, $value) {
        $this->conditions[] = "$field $operator ?";
        $this->params[] = $value;
        return $this;
    }

    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function get() {
        $sql = "SELECT {$this->fields} FROM {$this->table}";
        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
