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
        
        <div class="article-interactions">
            <?php 
            // Like functionality
            $like = new Like($db);
            $user_id = $_SESSION['user_id'] ?? null;
            $article_id = $article_data['id'];
            $likes_count = $like->countLikesForArticle($article_id);
            $user_liked = $user_id ? $like->hasUserLikedArticle($article_id, $user_id) : false;
            ?>
            
            <div class="like-section">
                <?php if ($user_id): ?>
                <form action="index.php?page=toggle-like" method="post" class="like-form">
                    <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                    <button type="submit" class="like-button <?php echo $user_liked ? 'liked' : ''; ?>">
                        ❤️ <?php echo $likes_count; ?> Likes
                    </button>
                </form>
                <?php else: ?>
                    <span>❤️ <?php echo $likes_count; ?> Likes</span>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="comments-section">
            <h3>Comments (<?php 
            $comment = new Comment($db);
            $comments_stmt = $comment->getCommentsForArticle($article_id);
            echo $comments_stmt->rowCount(); 
            ?>)</h3>
            
            <?php if ($user_id): ?>
            <form action="index.php?page=add-comment" method="post" class="add-comment-form">
                <input type="hidden" name="article_id" value="<?php echo $article_id; ?>">
                <textarea name="content" placeholder="Write a comment..." required></textarea>
                <button type="submit">Post Comment</button>
            </form>
            <?php endif; ?>
            
            <div class="comments-list">
                <?php 
                while ($comment_row = $comments_stmt->fetch(PDO::FETCH_ASSOC)): 
                ?>
                    <div class="comment">
                        <div class="comment-header">
                            <strong><?php echo htmlspecialchars($comment_row['username']); ?></strong>
                            <small><?php echo date('F j, Y H:i', strtotime($comment_row['created_at'])); ?></small>
                        </div>
                        <div class="comment-body">
                            <?php echo htmlspecialchars($comment_row['content']); ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
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

.article-interactions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding: 10px 0;
    border-top: 1px solid #eee;
    border-bottom: 1px solid #eee;
}

.like-section .like-button {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 1em;
    display: flex;
    align-items: center;
    gap: 5px;
}

.like-section .like-button.liked {
    color: red;
}

.comments-section {
    margin-top: 30px;
}

.add-comment-form {
    margin-bottom: 20px;
}

.add-comment-form textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    resize: vertical;
    min-height: 100px;
}

.add-comment-form button {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 20px;
    border-radius: 4px;
    cursor: pointer;
}

.comments-list .comment {
    background-color: #f9f9f9;
    border: 1px solid #eee;
    border-radius: 4px;
    padding: 15px;
    margin-bottom: 15px;
}

.comment-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    color: #666;
}

.comment-body {
    line-height: 1.6;
}
</style>
