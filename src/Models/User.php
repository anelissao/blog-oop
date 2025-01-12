<?php

class User {
    private $conn;
    private $table = 'users';
    
    // User properties
    public $id;
    public $username;
    public $email;
    public $password;
    public $role;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (username, email, password, role) 
                 VALUES (:username, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean and hash data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
        $this->role = htmlspecialchars(strip_tags($this->role));
        
        // Bind parameters
        $stmt->bindParam(':username', $this->username);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':password', $this->password);
        $stmt->bindParam(':role', $this->role);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function login($username, $password) {
        error_log("Login attempt - Username: " . $username);
        
        $query = "SELECT * FROM " . $this->table . " 
                 WHERE username = :username OR email = :username LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        
        if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            error_log("User found: " . print_r($row, true));
            if(password_verify($password, $row['password'])) {
                error_log("Password verified successfully");
                return $row;
            }
            error_log("Password verification failed");
            error_log("Input password: " . $password);
            error_log("Stored hash: " . $row['password']);
        } else {
            error_log("No user found with username/email: " . $username);
        }
        return false;
    }
    
    public function getUserById($id) {
        $query = "SELECT id, username, email, role, created_at FROM " . $this->table . " 
                 WHERE id = :id LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
