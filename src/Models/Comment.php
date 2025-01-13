<?php

class Comment {
    private $conn;
    private $table = 'comments';
    
    private $id;
    private $article_id;
    private $user_id;
    private $content;
    private $created_at;
    private $username; // To store author's username
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    // Getters
    public function getId() { return $this->id; }
    public function getArticleId() { return $this->article_id; }
    public function getUserId() { return $this->user_id; }
    public function getContent() { return $this->content; }
    public function getCreatedAt() { return $this->created_at; }
    public function getUsername() { return $this->username; }
    
    // Setters
    public function setId($id) { $this->id = $id; }
    public function setArticleId($article_id) { $this->article_id = $article_id; }
    public function setUserId($user_id) { $this->user_id = $user_id; }
    public function setContent($content) { $this->content = $content; }
    public function setCreatedAt($created_at) { $this->created_at = $created_at; }
    
    // Create a new comment
    public function create() {
        $query = "INSERT INTO " . $this->table . " 
                 (article_id, user_id, content) 
                 VALUES (:article_id, :user_id, :content)";
        
        $stmt = $this->conn->prepare($query);
        
        // Clean data
        $this->content = htmlspecialchars(strip_tags($this->content));
        
        // Bind data
        $stmt->bindParam(":article_id", $this->article_id);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":content", $this->content);
        
        if($stmt->execute()) {
            $this->id = $this->conn->lastInsertId();
            return true;
        }
        return false;
    }
    
    // Get comments for a specific article
    public function getCommentsForArticle($article_id) {
        $query = "SELECT c.*, u.username 
                  FROM " . $this->table . " c
                  JOIN users u ON c.user_id = u.id
                  WHERE c.article_id = :article_id
                  ORDER BY c.created_at DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":article_id", $article_id);
        $stmt->execute();
        
        return $stmt;
    }
}
