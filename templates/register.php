<h1>Register</h1>

<form action="index.php?page=register" method="POST" class="form">
    <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
    </div>
    
    <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
    </div>
    
    <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
    </div>
    
    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="index.php?page=login">Login here</a></p>
