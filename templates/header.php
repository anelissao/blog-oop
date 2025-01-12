<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blog System</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="nav-brand">
            <a href="index.php">Blog System</a>
        </div>
        <div class="nav-links">
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php?page=write-article" class="nav-link">Write New Article</a>
                <span class="nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="index.php?page=logout" class="nav-link">Logout</a>
            <?php else: ?>
                <a href="index.php?page=login" class="nav-link">Login</a>
                <a href="index.php?page=register" class="nav-link">Register</a>
            <?php endif; ?>
        </div>
    </nav>
    <div class="content">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

<style>
.navbar {
    background-color: #333;
    padding: 1rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand a {
    color: white;
    text-decoration: none;
    font-size: 1.5rem;
    font-weight: bold;
}

.nav-links {
    display: flex;
    gap: 1rem;
    align-items: center;
}

.nav-link {
    color: white;
    text-decoration: none;
    padding: 0.5rem 1rem;
}

.nav-link:hover {
    background-color: #555;
    border-radius: 4px;
}

.content {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.success-message {
    background-color: #d4edda;
    color: #155724;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 4px;
    border: 1px solid #c3e6cb;
}

span.nav-link {
    color: #aaa;
}
</style>
