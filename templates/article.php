<div class="article-container">
    <article class="full-article">
        <h1><?php echo htmlspecialchars($article_data['title']); ?></h1>
        
        <div class="article-meta">
            By <?php echo htmlspecialchars($article_data['author']); ?>
            on <?php echo date('F j, Y', strtotime($article_data['created_at'])); ?>
        </div>
        
        <?php if ($article_data['image_path']): ?>
            <div class="article-image">
                <img src="<?php echo htmlspecialchars($article_data['image_path']); ?>" alt="Article image">
            </div>
        <?php endif; ?>
        
        <div class="article-content">
            <?php echo nl2br(htmlspecialchars($article_data['content'])); ?>
        </div>
        
        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $article_data['user_id']): ?>
            <div class="article-actions">
                <a href="index.php?page=edit&id=<?php echo $article_data['id']; ?>" class="btn btn-primary">Edit</a>
                <a href="index.php?page=delete&id=<?php echo $article_data['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this article?')">Delete</a>
            </div>
        <?php endif; ?>
    </article>
</div>

<style>
.article-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.full-article {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    padding: 30px;
}

.full-article h1 {
    margin: 0 0 20px 0;
    color: #333;
    font-size: 2.5em;
}

.article-meta {
    color: #666;
    font-size: 0.9em;
    margin-bottom: 20px;
}

.article-image {
    margin: 20px 0;
    border-radius: 8px;
    overflow: hidden;
}

.article-image img {
    width: 100%;
    height: auto;
    display: block;
}

.article-content {
    line-height: 1.8;
    color: #444;
    margin-bottom: 30px;
}

.article-actions {
    margin-top: 30px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    transition: background-color 0.3s;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}
</style>
