<?php

class Article {
    private $conn;
    private $table = 'articles';
    
    // Article properties
    public $id;
    public $title;
    public $content;
    public $image_path;
    public $user_id;
    public $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function uploadImage($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
    
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowed_types)) {
            throw new Exception('Invalid file type. Only JPG, PNG and GIF are allowed.');
        }
    
        $max_size = 5 * 1024 * 1024; // 5MB
        if ($file['size'] > $max_size) {
            throw new Exception('File is too large. Maximum size is 5MB.');
        }
    
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/';
        $file_extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $file_name = uniqid() . '.' . $file_extension;
        $target_path = $upload_dir . $file_name;
    
        if (move_uploaded_file($file['tmp_name'], $target_path)) {
            return 'uploads/' . $file_name;
        }
    
        throw new Exception('Failed to upload file.');
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (title, content, image_path, user_id) 
                 VALUES (:title, :content, :image_path, :user_id)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        
        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':image_path', $this->image_path);
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
                 SET title = :title, content = :content";
        
        // Only update image if a new one is provided
        if ($this->image_path) {
            $query .= ", image_path = :image_path";
        }
        
        $query .= " WHERE id = :id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        
        // Bind parameters
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        if ($this->image_path) {
            $stmt->bindParam(':image_path', $this->image_path);
        }
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function delete() {
        // First get the image path
        $query = "SELECT image_path FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Delete the image file if it exists
        if ($row && $row['image_path']) {
            $image_path = $_SERVER['DOCUMENT_ROOT'] . '/' . $row['image_path'];
            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }
        
        // Delete the database record
        $query = "DELETE FROM " . $this->table . " WHERE id = :id AND user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
