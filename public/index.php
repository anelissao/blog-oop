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
            if ($user->login($_POST['username'], $_POST['password'])) {
                $_SESSION['user_id'] = $user->id;
                header('Location: index.php');
                exit;
            }
        }
        include __DIR__ . '/../templates/header.php';
        include __DIR__ . '/../templates/login.php';
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
