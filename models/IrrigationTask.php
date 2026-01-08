<?php
require_once '../config/database.php';

class IrrigationTask {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getTasksByDate($date) {
        $this->db->query('
            SELECT
                t.*,
                a.area_name,
                u.full_name as assigned_staff
            FROM irrigation_tasks as t
            JOIN irrigation_areas as a ON t.area_id = a.area_id
            JOIN users as u ON t.assigned_staff_id = u.user_id
            WHERE t.scheduled_date = :date
            ORDER BY t.scheduled_start_time
        ');
        $this->db->bind(':date', $date);
        return $this->db->resultSet();
    }

    public function getTaskById($id) {
        $this->db->query('SELECT * FROM irrigation_tasks WHERE task_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateTaskStatus($id, $status) {
        $this->db->query('UPDATE irrigation_tasks SET status = :status WHERE task_id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function logActivity($data) {
        $this->db->query('
            INSERT INTO activity_logs (task_id, user_id, actual_start_time, actual_end_time, area_served, issues_encountered, remarks, cancellation_reason)
            VALUES (:task_id, :user_id, :actual_start_time, :actual_end_time, :area_served, :issues_encountered, :remarks, :cancellation_reason)
        ');
        $this->db->bind(':task_id', $data['task_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':actual_start_time', $data['actual_start_time']);
        $this->db->bind(':actual_end_time', $data['actual_end_time']);
        $this->db->bind(':area_served', $data['area_served']);
        $this->db->bind(':issues_encountered', $data['issues_encountered']);
        $this->db->bind(':remarks', $data['remarks']);
        $this->db->bind(':cancellation_reason', $data['cancellation_reason']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
