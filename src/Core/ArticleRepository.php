<?php declare(strict_types=1);

namespace Tweakers\Core;

use Tweakers\DB\Repository;

class ArticleRepository extends Repository
{
    protected $table = 'articles';

    public function fetch(int $id) : Article
    {
        $row = $this->fetchById($id);

        return new Article(...$row);
    }
}
