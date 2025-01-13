<div class="container">
    <form class="login-form" action="index.php?page=login" method="POST">
        <h1>Login</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="success-message">
                <?php echo $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="username">Username or Email:</label>
            <input type="text" id="username" name="username" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Login</button>
            <a href="index.php?page=register" class="btn btn-secondary">Register</a>
        </div>
    </form>
</div>
