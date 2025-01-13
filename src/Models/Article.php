<?php

class Article {
    private $conn;
    private $table = 'articles';
    
    // Article properties
    private $id;
    private $title;
    private $content;
    private $image_path;
    private $user_id;
    private $created_at;
    private $comments_count;
    private $likes_count;
    private $user_liked;
    private $author;
    private $categories;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    // Getters
    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getImagePath() {
        return $this->image_path;
    }

    public function getUserId() {
        return $this->user_id;
    }

    public function getCreatedAt() {
        return $this->created_at;
    }

    public function getCommentsCount() {
        return $this->comments_count;
    }

    public function getLikesCount() {
        return $this->likes_count;
    }

    public function getUserLiked() {
        return $this->user_liked;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getCategories() {
        return $this->categories;
    }

    // Setters
    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function setImagePath($image_path) {
        $this->image_path = $image_path;
    }

    public function setUserId($user_id) {
        $this->user_id = $user_id;
    }

    public function setCreatedAt($created_at) {
        $this->created_at = $created_at;
    }
    
    public function uploadImage($file) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
    
        $upload_dir = 'uploads/';
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
    
        $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif');
    
        if (!in_array($file_extension, $allowed_extensions)) {
            return null;
        }
    
        $new_filename = uniqid() . '.' . $file_extension;
        $upload_path = $upload_dir . $new_filename;
    
        if (move_uploaded_file($file['tmp_name'], $upload_path)) {
            return $upload_path;
        }
    
        return null;
    }
    
    public function create() {
        $query = "INSERT INTO " . $this->table . "
                (title, content, image_path, user_id) 
                VALUES (:title, :content, :image_path, :user_id)";
    
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->image_path = htmlspecialchars(strip_tags($this->image_path));
    
        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':image_path', $this->image_path);
        $stmt->bindParam(':user_id', $this->user_id);
    
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function read($category_id = null, $search = null) {
        $query = "SELECT 
                    a.id, 
                    a.title, 
                    a.content,
                    a.image_path,
                    a.user_id, 
                    a.created_at,
                    u.username as author,
                    GROUP_CONCAT(DISTINCT c.name) as categories,
                    (SELECT COUNT(*) FROM comments WHERE article_id = a.id) as comments_count,
                    (SELECT COUNT(*) FROM likes WHERE article_id = a.id) as likes_count
                FROM 
                    " . $this->table . " a
                LEFT JOIN
                    users u ON a.user_id = u.id
                LEFT JOIN
                    article_categories ac ON a.id = ac.article_id
                LEFT JOIN
                    categories c ON ac.category_id = c.id";

        $conditions = [];
        $params = [];

        // Add category filter if specified
        if ($category_id) {
            $conditions[] = "ac.category_id = :category_id";
            $params[':category_id'] = $category_id;
        }

        // Add search condition if specified
        if ($search) {
            $conditions[] = "(a.title LIKE :search OR a.content LIKE :search)";
            $params[':search'] = "%{$search}%";
        }

        // Combine conditions
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " GROUP BY a.id ORDER BY a.created_at DESC";

        $stmt = $this->conn->prepare($query);
        
        // Bind parameters
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt;
    }
    
    public function readSingle() {
        $query = "SELECT 
                    a.id, 
                    a.title, 
                    a.content,
                    a.image_path,
                    a.user_id, 
                    a.created_at,
                    u.username as author,
                    GROUP_CONCAT(DISTINCT c.name) as categories,
                    (SELECT COUNT(*) FROM comments WHERE article_id = a.id) as comments_count,
                    (SELECT COUNT(*) FROM likes WHERE article_id = a.id) as likes_count
                FROM 
                    " . $this->table . " a
                LEFT JOIN
                    users u ON a.user_id = u.id
                LEFT JOIN
                    article_categories ac ON a.id = ac.article_id
                LEFT JOIN
                    categories c ON ac.category_id = c.id
                WHERE 
                    a.id = :id
                GROUP BY
                    a.id
                LIMIT 1";
    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
    
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->title = $row['title'];
            $this->content = $row['content'];
            $this->image_path = $row['image_path'];
            $this->user_id = $row['user_id'];
            $this->created_at = $row['created_at'];
            $this->comments_count = $row['comments_count'];
            $this->likes_count = $row['likes_count'];
            
            // Set author and categories
            $this->author = $row['author'];
            $this->categories = $row['categories'] ? explode(',', $row['categories']) : [];
            
            return true;
        }
        return false;
    }
    
    public function update() {
        $query = "UPDATE " . $this->table . "
                SET 
                    title = :title, 
                    content = :content,
                    image_path = :image_path
                WHERE 
                    id = :id AND user_id = :user_id";
    
        $stmt = $this->conn->prepare($query);
    
        // Clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->content = htmlspecialchars(strip_tags($this->content));
        $this->image_path = htmlspecialchars(strip_tags($this->image_path));
    
        // Bind data
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':content', $this->content);
        $stmt->bindParam(':image_path', $this->image_path);
        $stmt->bindParam(':id', $this->id);
        $stmt->bindParam(':user_id', $this->user_id);
    
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
    
    public function delete() {
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
