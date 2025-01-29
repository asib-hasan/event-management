<?php

class User {
    private $queryBuilder;

    public function __construct($pdo) {
        $this->queryBuilder = new QueryBuilder($pdo);
    }

    public function register($data) {
        return $this->queryBuilder->table('users')->insert($data);
    }

    public function findByEmail($email) {
        return $this->queryBuilder->table('users')->where('email', '=', $email)->get();
    }
}
