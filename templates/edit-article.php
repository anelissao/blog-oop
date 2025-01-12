<div class="container">
    <h1>Edit Article</h1>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="error-message">
            <?php echo $_SESSION['error']; ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <form action="index.php?page=edit&id=<?php echo htmlspecialchars($article_data['id']); ?>" method="POST" class="form" enctype="multipart/form-data">
        <div class="form-group">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required class="form-control" value="<?php echo htmlspecialchars($article_data['title']); ?>">
        </div>
        
        <div class="form-group">
            <label for="content">Content:</label>
            <textarea id="content" name="content" required class="form-control" rows="10"><?php echo htmlspecialchars($article_data['content']); ?></textarea>
        </div>
        
        <div class="form-group">
            <?php if ($article_data['image_path']): ?>
                <div class="current-image">
                    <label>Current Image:</label>
                    <img src="<?php echo htmlspecialchars($article_data['image_path']); ?>" alt="Current article image" style="max-width: 200px;">
                </div>
            <?php endif; ?>
            
            <label for="image">New Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*" class="form-control">
            <small class="form-text text-muted">Leave empty to keep the current image. Supported formats: JPG, PNG, GIF. Maximum size: 5MB</small>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Article</button>
        <a href="index.php?page=article&id=<?php echo $article_data['id']; ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<style>
.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
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

.current-image {
    margin-bottom: 15px;
}

.current-image img {
    display: block;
    margin-top: 10px;
    border-radius: 4px;
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
</style>
