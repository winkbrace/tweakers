<?php declare(strict_types=1);

namespace Tweakers\Core;

use Tweakers\DB\Repository;

class ArticleRepository extends Repository
{
    protected $table = 'articles';

    public function fetch(int $id) : Article
    {
        $sql = "select a.*
                ,      u.name author
                from   articles a 
                join   users u on a.user_id = u.id
                where  a.id = :id";

        $query = $this->pdo->prepare($sql);
        $query->execute([':id' => $id]);

        $row = $this->fetchRow($query);

        return new Article(...$row);
    }

    /**
     * @param int $limit
     * @return array|Article[] $articles
     */
    public function fetchMostRecent(int $limit = 10) : array
    {
        // PDO can't deal with variable limit, so we have to use the variable directly in the query.
        // Luckily, since we enforce it has to be an integer, we are safe from sql injection.
        $sql = "select a.* 
                ,      u.name author
                from   articles a
                join   users    u on a.user_id = u.id
                order by a.created_at desc
                ,      a.id desc
                limit  $limit";

        $query = $this->pdo->prepare($sql);
        $query->execute();

        $articles = [];
        while (! empty($row = $this->fetchRow($query))) {
            $articles[] = new Article(...$row);
        }

        return $articles;
    }
}
