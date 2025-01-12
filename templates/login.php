<h1>Login</h1>

<?php if (isset($_SESSION['error'])): ?>
    <div class="error-message">
        <?php echo $_SESSION['error']; ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['message'])): ?>
    <div class="success-message">
        <?php echo $_SESSION['message']; ?>
        <?php unset($_SESSION['message']); ?>
    </div>
<?php endif; ?>

<form action="index.php?page=login" method="POST" class="form">
    <div class="form-group">
        <label for="username">Username or Email:</label>
        <input type="text" id="username" name="username" required>
    </div>
    
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="index.php?page=register">Register here</a></p>
