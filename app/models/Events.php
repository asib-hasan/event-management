<?php

class Events {
    private $queryBuilder;

    public function __construct($pdo) {
        $this->queryBuilder = new QueryBuilder($pdo);
    }

    public function add($data) {
        return $this->queryBuilder->table('events')->insert($data);
    }
    public function getTotalEvents() {
        $totalEvents = $this->queryBuilder->table('events')->get();
        return count($totalEvents);
    }
    public function getEvents($start, $perPage) {
        $user_id = null;
        if(isset($_SESSION['user'])) {
            $user_id = $_SESSION['user']['id'];
        }
        return $this->queryBuilder->table('events')
            ->select('*')
            ->where('user_id','=',$user_id)
            ->limit($perPage)
            ->offset($start)
            ->get();
    }
    public function getSingle($id) {
        return $this->queryBuilder->table('events')
            ->where('id', '=', $id)
            ->first();
    }
    public function update($id, $data) {
        return $this->queryBuilder->table('events')
            ->where('id', '=', $id)
            ->update($data);
    }

    public function getPublicTotalEvents() {
        $totalPublicEvents = $this->queryBuilder
            ->where('event_date', '>=', date('Y-m-d'))
            ->table('events')->get();
        return count($totalPublicEvents);
    }

    public function getPublicEvents($start, $perPage) {
        return $this->queryBuilder->table('events')
            ->where('event_date', '>=', date('Y-m-d'))
            ->limit($perPage)
            ->offset($start)
            ->get();
    }
    public function delete($id) {
        return $this->queryBuilder->table('events')
            ->where('id', '=', $id)
            ->delete();
    }

}
