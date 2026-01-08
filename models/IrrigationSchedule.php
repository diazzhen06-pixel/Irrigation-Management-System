<?php
require_once '../config/database.php';

class IrrigationSchedule {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllSchedules() {
        $this->db->query('
            SELECT
                s.*,
                a.area_name
            FROM irrigation_schedules as s
            JOIN irrigation_areas as a ON s.area_id = a.area_id
            ORDER BY s.start_date DESC
        ');
        return $this->db->resultSet();
    }

    public function getScheduleById($id) {
        $this->db->query('SELECT * FROM irrigation_schedules WHERE schedule_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addSchedule($data) {
        $this->db->query('
            INSERT INTO irrigation_schedules (area_id, start_date, end_date, start_time, end_time, created_by)
            VALUES (:area_id, :start_date, :end_date, :start_time, :end_time, :created_by)
        ');
        // Bind values
        $this->db->bind(':area_id', $data['area_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':created_by', $data['created_by']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSchedule($data) {
        $this->db->query('
            UPDATE irrigation_schedules
            SET area_id = :area_id, start_date = :start_date, end_date = :end_date, start_time = :start_time, end_time = :end_time
            WHERE schedule_id = :schedule_id
        ');
        // Bind values
        $this->db->bind(':schedule_id', $data['schedule_id']);
        $this->db->bind(':area_id', $data['area_id']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':start_time', $data['start_time']);
        $this->db->bind(':end_time', $data['end_time']);
        $this->db->bind(':created_by', $data['created_by']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteSchedule($id) {
        $this->db->query('DELETE FROM irrigation_schedules WHERE schedule_id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
}
