<?php

namespace ChatApp\Models;

use PDO; 

class Message {
    private $db; // Property to hold the database connection

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to create a new message
    public function create($userId, $groupId, $message) {
        // Prepare and execute the SQL query to insert a new message
        $stmt = $this->db->prepare("INSERT INTO messages (user_id, group_id, message) VALUES (:user_id, :group_id, :message)");
        $stmt->execute(['user_id' => $userId, 'group_id' => $groupId, 'message' => $message]);

        // Return the ID of the newly created message
        return $this->db->lastInsertId();
    }

    // Method to fetch all messages for a specific group
    public function findByGroup($groupId) {
        // Prepare and execute the SQL query to fetch messages for a group
        $stmt = $this->db->prepare("SELECT * FROM messages WHERE group_id = :group_id ORDER BY timestamp");
        $stmt->execute(['group_id' => $groupId]);

        // Return the result as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}