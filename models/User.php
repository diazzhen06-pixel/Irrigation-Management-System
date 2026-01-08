<?php
require_once '../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Find user by username
    public function findUserByUsername($username) {
        $this->db->query('SELECT users.*, roles.role_name FROM users JOIN roles ON users.role_id = roles.role_id WHERE username = :username');
        $this->db->bind(':username', $username);

        $row = $this->db->single();

        // Check row
        if ($this->db->rowCount() > 0) {
            return $row;
        } else {
            return false;
        }
    }

    // Login User
    public function login($username, $password) {
        $row = $this->findUserByUsername($username);

        if ($row == false) {
            return false;
        }

        $hashed_password = $row->password_hash;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }
}
