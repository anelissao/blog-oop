<?php

class Article {
    private $conn;
    private $table = 'articles';
    
    // Article properties
    public $id;
    public $title;
    public $content;
    public $user_id;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (title, content, user_id) 
                 VALUES (:title, :content, :user_id)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        
        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':user_id', $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function read() {
        $query = "SELECT a.*, u.username as author 
                 FROM " . $this->table . " a
                 LEFT JOIN users u ON a.user_id = u.id
                 ORDER BY a.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function readOne() {
        $query = "SELECT a.*, u.username as author 
                 FROM " . $this->table . " a
                 LEFT JOIN users u ON a.user_id = u.id
                 WHERE a.id = :id
                 LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . "
                 SET title = :title, content = :content
                 WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        
        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function delete() {
        $query = "DELETE FROM " . $this->table . " 
                 WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
