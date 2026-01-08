<?php
require_once '../config/database.php';

class Alert {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function createAlert($type, $related_id, $message) {
        $this->db->query('INSERT INTO alerts (alert_type, related_id, message) VALUES (:alert_type, :related_id, :message)');
        $this->db->bind(':alert_type', $type);
        $this->db->bind(':related_id', $related_id);
        $this->db->bind(':message', $message);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getNewAlerts() {
        $this->db->query("SELECT * FROM alerts WHERE status = 'NEW' ORDER BY created_at DESC");
        return $this->db->resultSet();
    }
}
