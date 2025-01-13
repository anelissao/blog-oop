<?php

class Like {
    private $conn;
    private $table = 'likes';
    
    private $id;
    private $article_id;
    private $user_id;
    private $created_at;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getArticleId() { return $this->article_id; }
    public function getUserId() { return $this->user_id; }
    public function getCreatedAt() { return $this->created_at; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setArticleId($article_id) { $this->article_id = $article_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    
    // Add a like
    public function addLike() {
        // First, check if user has already liked the article
        $checkQuery = "SELECT id FROM " . $this->table . " 
                       WHERE article_id = :article_id AND user_id = :user_id";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":article_id", $this->article_id);
        $checkStmt->bindParam(":user_id", $this->user_id);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            // User has already liked, so remove the like (toggle)
            return $this->removeLike();
        }
        
        // Add new like
        $query = "INSERT INTO " . $this->table . " 
                 (article_id, user_id) 
                 VALUES (:article_id, :user_id)";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":article_id", $this->article_id);
        $stmt->bindParam(":user_id", $this->user_id);
        
        return $stmt->execute();
    }
    
    // Remove a like
    public function removeLike() {
        $query = "DELETE FROM " . $this->table . " 
                  WHERE article_id = :article_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(":article_id", $this->article_id);
        $stmt->bindParam(":user_id", $this->user_id);
        
        return $stmt->execute();
    }
    
    // Count likes for an article
    public function countLikesForArticle($article_id) {
        $query = "SELECT COUNT(*) as like_count 
                  FROM " . $this->table . " 
                  WHERE article_id = :article_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":article_id", $article_id);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['like_count'];
    }
    
    // Check if user has liked an article
    public function hasUserLikedArticle($article_id, $user_id) {
        $query = "SELECT id 
                  FROM " . $this->table . " 
                  WHERE article_id = :article_id AND user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":article_id", $article_id);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();
        
        return $stmt->rowCount() > 0;
    }
}
