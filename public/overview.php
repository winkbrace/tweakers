<?php
require_once __DIR__ . '/../vendor/autoload.php';

$repo = new \Tweakers\Core\ArticleRepository(\Tweakers\DB\Connection::get());
$articles = $repo->fetchMostRecent();

?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Overview</title>
    <link href="css/style.css" rel="stylesheet">
</head>
<body>

    <div class="content">
        <?php foreach ($articles as $article): ?>
            <div class="article" id="article-<?= $article->id() ?>">
                <h2><?= $article->title() ?></h2>
                <p><small>By: <?= $article->author() ?></small></p>
                <p class="body"><?= $article->body() ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        elements = document.querySelectorAll('div.article');
        for (var i = 0, len = elements.length; i < len; i++) {
            var e = elements[i];
            e.addEventListener('click', function() {
                window.location.href = 'article.php?id=' + this.id.substr(8);
            }, false);
        }
    </script>

</body>
</html>
