<div class="container">
    <form class="register-form" action="index.php?page=register" method="POST">
        <h1>Register</h1>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required class="form-control">
        </div>

        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required class="form-control">
        </div>

        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required class="form-control">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Register</button>
            <a href="index.php?page=login" class="btn btn-secondary">Back to Login</a>
        </div>
    </form>
</div>
