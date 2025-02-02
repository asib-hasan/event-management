<?php

class Attendee {
    private $queryBuilder;

    public function __construct($pdo) {
        $this->queryBuilder = new QueryBuilder($pdo);
    }

    public function add($data) {
        return $this->queryBuilder->table('attendees')->insert($data);
    }
    public function getAttendees($event_id) {
        $attendees = $this->queryBuilder->table('attendees')
            ->select('*')
            ->where('event_id','=',$event_id)
            ->get();
        return $attendees;
    }
}
