<?php declare(strict_types=1);

namespace Tweakers\Core;

use Tweakers\Common\OrderDirection;
use Tweakers\DB\Repository;

class CommentRepository extends Repository
{
    const PAGINATION_LIMIT = 500;

    protected $table = 'comments';

    /**
     * @param int $articleId
     * @param int $page
     * @param null|OrderDirection $order
     * @param int $minimumScore
     * @return array|Comment[]
     */
    public function fetchByArticleId(int $articleId, int $minimumScore = -1, int $page = 0, ?OrderDirection $order = null) : array
    {
        // We add pagination, but hardcode the limit to 500 comments per page.
        // Should be no problem to load 500 comments in memory.
        $offset = $this->calculateOffset($page, self::PAGINATION_LIMIT);
        $order = $order ?: OrderDirection::ASC();

        $sql = "select c.id
                ,      c.user_id
                ,      c.article_id
                ,      c.parent_comment_id
                ,      c.title
                ,      c.body
                ,      c.created_at
                ,      u.name                              author
                ,      round(ifnull(avg(cs.score), 0), 1)  average_score
                from   comments c
                join   users    u on c.user_id = u.id
                left join comment_scores cs on c.id = cs.comment_id
                where  c.article_id = :article_id
                group by c.id
                ,      c.user_id
                ,      c.article_id
                ,      c.parent_comment_id
                ,      c.title
                ,      c.body
                ,      c.created_at
                ,      u.name
                having average_score >= :min_score
                order by created_at " . $order->toString() . "
                ,        id " . $order->toString() . "
                limit  " . self::PAGINATION_LIMIT . "
                offset $offset";

        $query = $this->pdo->prepare($sql);
        $query->execute([':article_id' => $articleId, ':min_score' => $minimumScore]);

        $comments = $this->collect($query);

        return $this->buildTree($comments);
    }

    /**
     * @param \PDOStatement $query
     * @return array|Comment[]
     */
    private function collect(\PDOStatement $query) : array
    {
        $comments = [];
        while (! empty($row = $this->fetchRow($query))) {
            $comments[] = new Comment(...$row);
        }

        return $comments;
    }

    /**
     * Unfortunately mysql lacks hierarchical functions, so we have to build the tree in php.
     *
     * @param array|Comment[] $comments
     * @param int $parentId
     * @return array $tree
     */
    private function buildTree(array &$comments, int $parentId = 0) : array
    {
        $tree = [];
        foreach ($comments as $comment) {
            if ($comment->parentCommentId() == $parentId) {
                $comment->setChildren($this->buildTree($comments, $comment->id()));
                $tree[] = $comment;
            }
        }

        return $tree;
    }
}
