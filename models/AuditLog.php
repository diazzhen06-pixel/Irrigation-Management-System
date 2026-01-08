<?php
require_once '../config/database.php';

class AuditLog {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function logAction($user_id, $action, $target_resource = null, $target_id = null, $change_details = null) {
        $this->db->query('
            INSERT INTO audit_logs (user_id, action, target_resource, target_id, change_details, ip_address)
            VALUES (:user_id, :action, :target_resource, :target_id, :change_details, :ip_address)
        ');
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':action', $action);
        $this->db->bind(':target_resource', $target_resource);
        $this->db->bind(':target_id', $target_id);
        $this->db->bind(':change_details', $change_details);
        $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
