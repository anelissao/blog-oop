<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Blog</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
</head>
<body>
    <header>
        <div class="header-content">
            <a href="index.php" class="site-title">MyBlog</a>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li><a href="index.php?page=write-article">Write Article</a></li>
                        <li><a href="index.php?page=logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="index.php?page=login">Login</a></li>
                        <li><a href="index.php?page=register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    
    <main>
        <?php if (isset($_SESSION['message'])): ?>
            <div class="container">
                <div class="message success-message">
                    <?php 
                    echo $_SESSION['message'];
                    unset($_SESSION['message']);
                    ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="content">
            <?php // Rest of the content remains the same ?>
        </div>
    </main>

<style>
/* Add styles here */
.header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.site-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #333;
    text-decoration: none;
}

nav ul {
    list-style: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 1rem;
}

nav a {
    color: #333;
    text-decoration: none;
}

nav a:hover {
    color: #555;
}

.message {
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
}
</style>
