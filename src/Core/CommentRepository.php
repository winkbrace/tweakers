<?php declare(strict_types=1);

namespace Tweakers\Core;

use Tweakers\DB\Repository;

class CommentRepository extends Repository
{
    protected $table = 'comments';

    public function fetch(int $id) : Comment
    {
        $row = $this->fetchById($id);

        return new Comment(...$row);
    }
}
