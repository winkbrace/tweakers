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
        <table>
            <thead>
                <tr>
                    <th>Author</th>
                    <th>Title</th>
                    <th>Content</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr id="article-<?= $article->id() ?>">
                    <td><?= $article->author() ?></td>
                    <td><?= $article->title() ?></td>
                    <td><?= $article->body() ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script>
        elements = document.getElementsByTagName('tr');
        for (var i = 0, len = elements.length; i < len; i++) {
            var e = elements[i];
            e.addEventListener('click', function() {
                window.location.href = 'article.php?id=' + this.id.substr(8);
            }, false);
        }
    </script>

</body>
</html>
