<div class="container">
    <form class="form-container" action="index.php?page=write-article" method="POST" enctype="multipart/form-data">
        <h1>Write New Article</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>

        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required class="form-control" placeholder="Enter article title">
        </div>
        
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" required class="form-control" rows="10" placeholder="Write your article content here..."></textarea>
        </div>
        
        <div class="form-group">
            <label for="image">Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*" class="form-control">
            <small class="form-text text-muted">Supported formats: JPG, PNG, GIF. Maximum size: 5MB</small>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Publish Article</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.form-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.form-group {
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

textarea.form-control {
    resize: vertical;
    min-height: 200px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    margin-right: 10px;
    text-decoration: none;
    display: inline-block;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.error-message {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #f5c6cb;
    border-radius: 4px;
}

.form-text {
    font-size: 0.875rem;
    color: #6c757d;
    margin-top: 4px;
}

.form-actions {
    margin-top: 20px;
    text-align: center;
}
</style>
