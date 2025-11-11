<?php

class Report {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function create($productId, $userId, $reason, $description) {
        $sql = "INSERT INTO reports (product_id, user_id, reason, description) VALUES (?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$productId, $userId, $reason, $description]);
    }
    
    public function existsForUser($productId, $userId) {
        if (!$userId) return false;
        
        $sql = "SELECT COUNT(*) FROM reports WHERE product_id = ? AND user_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$productId, $userId]);
        return $stmt->fetchColumn() > 0;
    }
}