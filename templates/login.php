<h1>Login</h1>

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
