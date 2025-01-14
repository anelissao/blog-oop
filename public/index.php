<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Article.php';
require_once __DIR__ . '/../src/Models/Comment.php';
require_once __DIR__ . '/../src/Models/Like.php';

// Initialize database connection
$database = Database::getInstance();
$db = $database->getConnection();

// Get the current page from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Error handling function
function handleError($message) {
    $_SESSION['error'] = $message;
    header('Location: index.php');
    exit;
}

// Home page
if ($page === 'home') {
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/home.php';
    include __DIR__ . '/../templates/footer.php';
}

// Login page
elseif ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = new User($db);
        $userData = $user->login($_POST['username'], $_POST['password']);
        if ($userData) {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['role'] = $userData['role'];
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid username or password';
        }
    }
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/login.php';
    include __DIR__ . '/../templates/footer.php';
}

// Register page
elseif ($page === 'register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = new User($db);
        $user->setUsername($_POST['username']);
        $user->setEmail($_POST['email']);
        $user->setPassword($_POST['password']);
        $user->setRole('user');
        
        if ($user->create()) {
            $_SESSION['message'] = 'Registration successful! Please login.';
            header('Location: index.php?page=login');
            exit;
        } else {
            handleError('Registration failed');
        }
    }
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/register.php';
    include __DIR__ . '/../templates/footer.php';
}

// Write Article page
elseif ($page === 'write-article') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $article = new Article($db);
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setUserId($_SESSION['user_id']);
            
            // Handle image upload if present
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $image_path = $article->uploadImage($_FILES['image']);
                $article->setImagePath($image_path);
            }
            
            if ($article->create()) {
                $_SESSION['message'] = 'Article created successfully!';
                header('Location: index.php');
                exit;
            } else {
                handleError('Failed to create article');
            }
        } catch (Exception $e) {
            handleError($e->getMessage());
        }
    }
    
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/write-article.php';
    include __DIR__ . '/../templates/footer.php';
}

// Article page
elseif ($page === 'article') {
    $article = new Article($db);
    $article->setId(isset($_GET['id']) ? $_GET['id'] : 0);
    
    if (!$article->readSingle()) {
        header('Location: index.php?page=404');
        exit;
    }
    
    $article_data = [
        'id' => $article->getId(),
        'title' => $article->getTitle(),
        'content' => $article->getContent(),
        'image_path' => $article->getImagePath(),
        'user_id' => $article->getUserId(),
        'created_at' => $article->getCreatedAt(),
        'author' => $article->getAuthor(),
        'categories' => $article->getCategories(),
        'comments_count' => $article->getCommentsCount(),
        'likes_count' => $article->getLikesCount()
    ];
    
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/article.php';
    include __DIR__ . '/../templates/footer.php';
}

// Edit Article page
elseif ($page === 'edit') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    $article = new Article($db);
    $article->setId(isset($_GET['id']) ? $_GET['id'] : 0);
    $article_data = $article->readSingle();
    
    // Check if article exists and belongs to user
    if (!$article_data || $article->getUserId() != $_SESSION['user_id']) {
        header('Location: index.php?page=404');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        try {
            $article->setTitle($_POST['title']);
            $article->setContent($_POST['content']);
            $article->setUserId($_SESSION['user_id']);
            
            // Handle image upload if present
            if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $image_path = $article->uploadImage($_FILES['image']);
                $article->setImagePath($image_path);
            }
            
            if ($article->update()) {
                $_SESSION['message'] = 'Article updated successfully!';
                header('Location: index.php?page=article&id=' . $article->getId());
                exit;
            } else {
                handleError('Failed to update article');
            }
        } catch (Exception $e) {
            handleError($e->getMessage());
        }
    }
    
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/edit-article.php';
    include __DIR__ . '/../templates/footer.php';
}

// Delete Article
elseif ($page === 'delete') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    if (isset($_GET['id'])) {
        $article = new Article($db);
        $article->setId($_GET['id']);
        $article->setUserId($_SESSION['user_id']);
        
        if ($article->delete()) {
            $_SESSION['message'] = 'Article deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete article';
        }
    }
    
    header('Location: index.php');
    exit;
}

// Add Comment
elseif ($page === 'add-comment') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $comment = new Comment($db);
        $comment->setArticleId($_POST['article_id']);
        $comment->setUserId($_SESSION['user_id']);
        $comment->setContent($_POST['content']);
        
        if ($comment->create()) {
            $_SESSION['success'] = 'Comment added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add comment';
        }
        
        header('Location: index.php?page=article&id=' . $_POST['article_id']);
        exit;
    }
}

// Toggle Like
elseif ($page === 'toggle-like') {
    if (!isset($_SESSION['user_id'])) {
        header('Location: index.php?page=login');
        exit;
    }
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $like = new Like($db);
        $like->setArticleId($_POST['article_id']);
        $like->setUserId($_SESSION['user_id']);
        
        if ($like->addLike()) {
            $_SESSION['success'] = 'Like toggled successfully';
        } else {
            $_SESSION['error'] = 'Failed to toggle like';
        }
        
        header('Location: index.php?page=article&id=' . $_POST['article_id']);
        exit;
    }
}

// Logout
elseif ($page === 'logout') {
    session_destroy();
    header('Location: index.php');
    exit;
}

// 404 Page
else {
    include __DIR__ . '/../templates/header.php';
    include __DIR__ . '/../templates/404.php';
    include __DIR__ . '/../templates/footer.php';
}
