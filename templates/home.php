<?php
$article = new Article($db);
$result = $article->read();
?>

<h1>Latest Articles</h1>

<?php if ($result->rowCount() > 0): ?>
    <?php while ($row = $result->fetch(PDO::FETCH_ASSOC)): ?>
        <article class="article">
            <h2><?php echo htmlspecialchars($row['title']); ?></h2>
            <p class="meta">By <?php echo htmlspecialchars($row['author']); ?> on <?php echo date('F j, Y', strtotime($row['created_at'])); ?></p>
            <div class="content">
                <?php echo nl2br(htmlspecialchars($row['content'])); ?>
            </div>
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $row['user_id']): ?>
                <div class="actions">
                    <a href="index.php?page=edit&id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="index.php?page=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            <?php endif; ?>
        </article>
    <?php endwhile; ?>
<?php else: ?>
    <p>No articles found.</p>
<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>
    <div class="new-article">
        <a href="index.php?page=new" class="button">Write New Article</a>
    </div>
<?php endif; ?>
