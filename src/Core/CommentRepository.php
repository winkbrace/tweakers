<?php declare(strict_types=1);

namespace Tweakers\Core;

use Tweakers\DB\Repository;

class CommentRepository extends Repository
{
    protected $table = 'comments';

    public function fetch(int $id) : Comment
    {
        $sql = "select c.*
                ,      u.name author
                from   comments c
                join   users    u on c.user_id = u.id
                where  c.id = :id";

        $query = $this->pdo->prepare($sql);
        $query->execute([':id' => $id]);

        $row = $this->fetchRow($query);

        return new Comment(...$row);
    }
}
