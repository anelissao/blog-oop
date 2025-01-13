<?php

class User {
    private $conn;
    private $table = 'users';
    
    // User properties
    private $id;
    private $username;
    private $email;
    private $password;
    private $role;
    private $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getRole() {
        return $this->role;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setPassword($password) {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (username, email, password, role) 
                 VALUES (:username, :email, :password, :role)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->username = htmlspecialchars(strip_tags($this->username));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->role = htmlspecialchars(strip_tags($this->role));
        
        // Bind data
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":password", $this->password);
        $stmt->bindParam(":role", $this->role);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function login($username, $password) {
        $query = "SELECT id, username, password, role FROM " . $this->table . " WHERE username = :username";
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $row['password'])) {
                $this->setId($row['id']);
                $this->setUsername($row['username']);
                $this->setRole($row['role']);
                
                return [
                    'id' => $this->getId(),
                    'username' => $this->getUsername(),
                    'role' => $this->getRole()
                ];
            }
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
