<?php

class QueryBuilder {
    private $pdo;
    private $table;
    private $fields = "*";
    private $conditions = [];
    private $params = [];
    private $limit;
    private $offset;

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

    public function first() {
        $sql = "SELECT {$this->fields} FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }
        if ($this->limit && $this->limit !== 1) {
            $this->limit(1);
        }
        $sql .= " LIMIT 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function limit($limit) {
        $this->limit = (int) $limit;
        return $this;
    }

    public function offset($offset) {
        $this->offset = (int) $offset;
        return $this;
    }

    public function insert($data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));
        $sql = "INSERT INTO {$this->table} ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute(array_values($data));
    }

    public function update($data) {
        $set = [];
        foreach ($data as $column => $value) {
            $set[] = "$column = ?";
        }

        $sql = "UPDATE {$this->table} SET " . implode(", ", $set);

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        $params = array_merge(array_values($data), $this->params);

        return $stmt->execute($params);
    }

    public function delete() {
        $sql = "DELETE FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($this->params);
    }

    public function get() {
        $sql = "SELECT {$this->fields} FROM {$this->table}";

        if (!empty($this->conditions)) {
            $sql .= " WHERE " . implode(" AND ", $this->conditions);
        }

        if (isset($this->limit)) {
            $sql .= " LIMIT {$this->limit}";
        }

        if (isset($this->offset)) {
            $sql .= " OFFSET {$this->offset}";
        }
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($this->params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
