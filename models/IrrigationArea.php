<?php
require_once '../config/database.php';

class IrrigationArea {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllAreas() {
        $this->db->query('SELECT * FROM irrigation_areas ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function getAreaById($id) {
        $this->db->query('SELECT * FROM irrigation_areas WHERE area_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addArea($data) {
        $this->db->query('INSERT INTO irrigation_areas (area_name, barangay_coverage, estimated_hectares, notes, area_type, parent_id) VALUES (:area_name, :barangay_coverage, :estimated_hectares, :notes, :area_type, :parent_id)');
        // Bind values
        $this->db->bind(':area_name', $data['area_name']);
        $this->db->bind(':barangay_coverage', $data['barangay_coverage']);
        $this->db->bind(':estimated_hectares', $data['estimated_hectares']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':area_type', $data['area_type']);
        $this->db->bind(':parent_id', $data['parent_id']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateArea($data) {
        $this->db->query('UPDATE irrigation_areas SET area_name = :area_name, barangay_coverage = :barangay_coverage, estimated_hectares = :estimated_hectares, notes = :notes, area_type = :area_type, parent_id = :parent_id WHERE area_id = :area_id');
        // Bind values
        $this->db->bind(':area_id', $data['area_id']);
        $this->db->bind(':area_name', $data['area_name']);
        $this->db->bind(':barangay_coverage', $data['barangay_coverage']);
        $this->db->bind(':estimated_hectares', $data['estimated_hectares']);
        $this->db->bind(':notes', $data['notes']);
        $this->db->bind(':area_type', $data['area_type']);
        $this->db->bind(':parent_id', $data['parent_id']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteArea($id) {
        $this->db->query('DELETE FROM irrigation_areas WHERE area_id = :id');
        // Bind values
        $this->db->bind(':id', $id);

        // Execute
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
