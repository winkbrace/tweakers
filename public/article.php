<?php
require_once __DIR__ . '/../vendor/autoload.php';

try {
    $articleRepo = new \Tweakers\Core\ArticleRepository(\Tweakers\DB\Connection::get());
    $article = $articleRepo->fetch((int) $_GET['id']);

    // I like get variables for sorting and filtering, so that you can link to a specific view.
    $order = (empty($_GET['sort']) || $_GET['sort'] == 'desc') ? \Tweakers\Common\OrderDirection::DESC() : \Tweakers\Common\OrderDirection::ASC();
    $minScore = $_GET['minscore'] ?? -1;

    $commentRepo = new \Tweakers\Core\CommentRepository(\Tweakers\DB\Connection::get());
    $comments = $commentRepo->fetchByArticleId($article->id(), $minScore, 0, $order);
} catch (\Tweakers\Exception\EntityNotFound $e) {
    header('Location: 404.php');
    die;
}

function renderComment(\Tweakers\Core\Comment $comment, $depth = 0) : void
{
    echo   '<div class="comment" style="margin-left: '. (20 * $depth) .'px;">
                <h4>'. $comment->title() .'</h4>
                <p class="author"><small>By: '. $comment->author() .' | '. $comment->createdAt()->format('d-m-Y H:i') .' | Score: '. $comment->averageScore() .'</small></p>
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
        <div class="filters">
            Filter by score:
            <?php foreach([-1, 0, 1, 2, 3] as $score): ?>
            <a href="article.php?id=<?= $_GET['id'] ?>&sort=<?= $order->toString() ?>&minscore=<?= $score ?>"><?= $score ?></a>
            <?php endforeach; ?>

            |
            <a href="article.php?id=<?= $_GET['id'] ?>&minscore=<?= $_GET['minscore'] ?? -1 ?>&sort=<?= $order->isAsc() ? 'desc' : 'asc' ?>">
                sort <?= $order->isAsc() ? 'descending' : 'ascending' ?></a>
        </div>
        <?php foreach ($comments as $comment): ?>
            <?php renderComment($comment); ?>
        <?php endforeach; ?>
    </div>
</div>

</body>
</html>
