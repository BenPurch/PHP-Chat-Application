<?php

namespace ChatApp\Models;

use PDO; 

class Group {
    private $db; // Property to hold the database connection

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to create a new group
    public function create($name) {
        // Prepare and execute the SQL query to insert a new group
        $stmt = $this->db->prepare("INSERT INTO groups (name) VALUES (:name)");
        $stmt->execute(['name' => $name]);

        // Return the ID of the newly created group
        return $this->db->lastInsertId();
    }

    // Method to fetch all groups
    public function findAll() {
        // Execute the SQL query to fetch all groups
        $stmt = $this->db->query("SELECT * FROM groups");

        // Return the result as an associative array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Method to add a user to a group
    public function addMember($userId, $groupId) {
        // Prepare and execute the SQL query to add a user to a group
        $stmt = $this->db->prepare("INSERT INTO group_members (user_id, group_id) VALUES (:user_id, :group_id)");
        $stmt->execute(['user_id' => $userId, 'group_id' => $groupId]);
    }
}