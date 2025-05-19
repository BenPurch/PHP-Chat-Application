<?php

namespace ChatApp\Database;

use PDO; 

class Database {
    private $pdo; // Property to hold the PDO instance

    // Constructor to initialize the PDO connection
    public function __construct() {
        try {
            // Connect to the SQLite database
            $this->pdo = new PDO('sqlite:chat.db');
            // Set PDO to throw exceptions on errors
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Log success
            error_log("Database connection successful");
            // Initialize the schema
            $this->initializeSchema();
        } catch (\PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            throw $e; // Rethrow the exception
        }
    }

    // Method to get the PDO connection
    public function getConnection(): PDO {
        return $this->pdo;
    }

    // Initialize the schema each time to make sure there is allways a database
    public function initializeSchema() {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT NOT NULL UNIQUE,
                token TEXT NOT NULL UNIQUE
            );
            
            CREATE TABLE IF NOT EXISTS groups (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL
            );
            
            CREATE TABLE IF NOT EXISTS group_members (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                group_id INTEGER NOT NULL,
                FOREIGN KEY (user_id) REFERENCES users (id),
                FOREIGN KEY (group_id) REFERENCES groups (id),
                UNIQUE (user_id, group_id)
            );
            
            CREATE TABLE IF NOT EXISTS messages (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                user_id INTEGER NOT NULL,
                group_id INTEGER NOT NULL,
                message TEXT NOT NULL,
                timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users (id),
                FOREIGN KEY (group_id) REFERENCES groups (id)
            );
        ");
}
}