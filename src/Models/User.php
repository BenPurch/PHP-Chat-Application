<?php

namespace ChatApp\Models;

use PDO;

class User {
    private $db; // Property to hold the database connection

    // Constructor to initialize the database connection
    public function __construct($db) {
        $this->db = $db;
    }

    // Method to create a new user
    public function create($username, $token) {
        // Prepare and execute the SQL query to insert a new user
        $stmt = $this->db->prepare("INSERT INTO users (username, token) VALUES (:username, :token)");
        $stmt->execute(['username' => $username, 'token' => $token]);

        // Return the ID of the newly created user
        return $this->db->lastInsertId();
    }

    // Method to find a user by their token
    public function findByToken($token) {
        // Prepare and execute the SQL query to find a user by token
        $stmt = $this->db->prepare("SELECT * FROM users WHERE token = :token");
        $stmt->execute(['token' => $token]);

        // Return the user data as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Method to find a user by their username
    public function findByUsername($username) {
        // Prepare and execute the SQL query to find a user by username
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        // Return the user data as an associative array
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}