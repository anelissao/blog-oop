<?php
$article = new Article($db);
$result = $article->read();
?>

<div class="articles">
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <article class="article-card">
            <?php if ($row['image_path']): ?>
                <div class="article-image">
                    <img src="<?php echo htmlspecialchars($row['image_path']); ?>" alt="Article image">
                </div>
            <?php endif; ?>
            <div class="article-content">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <p class="article-meta">
                    By <?php echo htmlspecialchars($row['author']); ?> 
                    on <?php echo date('F j, Y', strtotime($row['created_at'])); ?>
                </p>
                <div class="article-excerpt">
                    <?php echo nl2br(htmlspecialchars(substr($row['content'], 0, 200))); ?>...
                </div>
                <a href="index.php?page=article&id=<?php echo $row['id']; ?>" class="read-more">Read More</a>
                <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
                    <div class="actions">
                        <a href="index.php?page=edit&id=<?php echo $row['id']; ?>">Edit</a>
                        <a href="index.php?page=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                <?php endif; ?>
            </div>
        </article>
    <?php endwhile; ?>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="new-article">
        <a href="index.php?page=new" class="button">Write New Article</a>
    </div>
<?php endif; ?>

<style>
.articles {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
}

.article-card {
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    margin-bottom: 20px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.article-image {
    width: 100%;
    height: 300px;
    overflow: hidden;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.article-content {
    padding: 20px;
}

.article-content h2 {
    margin: 0 0 10px 0;
    color: #333;
}

.article-meta {
    color: #666;
    font-size: 0.9em;
    margin-bottom: 15px;
}

.article-excerpt {
    color: #444;
    line-height: 1.6;
    margin-bottom: 15px;
}

.read-more {
    display: inline-block;
    padding: 8px 16px;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    transition: background-color 0.3s;
}

.read-more:hover {
    background-color: #0056b3;
}
</style>
