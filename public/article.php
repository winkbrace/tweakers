<?php
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $articleRepo = new \Tweakers\Core\ArticleRepository(\Tweakers\DB\Connection::get());
    $article = $articleRepo->fetch((int) $_GET['id']);

    $commentRepo = new \Tweakers\Core\CommentRepository(\Tweakers\DB\Connection::get());
    $comments = $commentRepo->fetchByArticleId($article->id());
} catch (\Tweakers\Exception\EntityNotFound $e) {
    header('Location: 404.php');
    die;
}

function renderComment(\Tweakers\Core\Comment $comment, $depth = 0) : void
{
    echo   '<div class="comment" style="margin-left: '. (20 * $depth) .'px;">
                <h4>'. $comment->title() .'</h4>
                <p class="author"><small>By: '. $comment->author() .' | Score: '. $comment->averageScore() .'</small></p>
                <p class="body">'. $comment->body() .'</p>
            </div>';

    if ($comment->hasChildren()) {
        foreach ($comment->children() as $child) {
            renderComment($child, $depth + 1);
        }
    }
}

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overview</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

<div class="content">

    <p><small><a href="overview.php">< Home</a></small></p>

    <!-- The article -->
    <div class="article">
        <h2><?= $article->title() ?></h2>
        <p><small>By: <?= $article->author() ?></small></p>
        <p class="body"><?= $article->body() ?></p>
    </div>

    <!-- The comments -->
    <div class="comments">
        <h3>Comments</h3>
        <?php foreach ($comments as $comment): ?>
            <?php renderComment($comment); ?>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
