<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../src/Models/User.php';
require_once __DIR__ . '/../src/Models/Article.php';

// Initialize database connection
$database = Database::getInstance();
$db = $database->getConnection();

// Get the current page from URL
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Basic routing
switch ($page) {
    case 'home':
        include __DIR__ . '/../templates/header.php';
        include __DIR__ . '/../templates/home.php';
        include __DIR__ . '/../templates/footer.php';
        break;
        
    case 'login':
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
        break;
        
    case 'write-article':
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article = new Article($db);
            $article->title = $_POST['title'];
            $article->content = $_POST['content'];
            $article->user_id = $_SESSION['user_id'];
            
            if ($article->create()) {
                $_SESSION['message'] = 'Article created successfully!';
                header('Location: index.php');
                exit;
            } else {
                $_SESSION['error'] = 'Failed to create article';
            }
        }
        
        include __DIR__ . '/../templates/header.php';
        include __DIR__ . '/../templates/write-article.php';
        include __DIR__ . '/../templates/footer.php';
        break;
        
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User($db);
            $user->username = $_POST['username'];
            $user->email = $_POST['email'];
            $user->password = $_POST['password'];
            $user->role = 'user';
            
            if ($user->create()) {
                $_SESSION['message'] = 'Registration successful! Please login.';
                header('Location: index.php?page=login');
                exit;
            }
        }
        include __DIR__ . '/../templates/header.php';
        include __DIR__ . '/../templates/register.php';
        include __DIR__ . '/../templates/footer.php';
        break;
        
    case 'logout':
        session_destroy();
        header('Location: index.php');
        exit;
        break;
        
    default:
        include __DIR__ . '/../templates/header.php';
        include __DIR__ . '/../templates/404.php';
        include __DIR__ . '/../templates/footer.php';
        break;
}