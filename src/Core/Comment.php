<?php declare(strict_types=1);

namespace Tweakers\Core;

use DateTimeImmutable;

/**
 * This entity represents a comment
 */
class Comment
{
    /** @var int */
    private $id;
    /** @var int */
    private $userId;
    /** @var int */
    private $articleId;
    /** @var null|int */
    private $parentCommentId;
    /** @var string */
    private $title;
    /** @var string */
    private $body;
    /** @var DateTimeImmutable */
    private $createdAt;
    /** @var string */
    private $author;
    /** @var float */
    private $averageScore;
    /** @var array */
    private $children = [];

    public function __construct(int $id, int $userId, int $articleId, ?int $parentCommentId, string $title, string $body, string $createdAt, string $author, float $averageScore)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->articleId = $articleId;
        $this->parentCommentId = $parentCommentId;
        $this->title = $title;
        $this->body = $body;
        $this->createdAt = new DateTimeImmutable($createdAt);
        $this->author = $author;
        $this->averageScore = $averageScore;
    }

    public function id() : int
    {
        return $this->id;
    }

    public function userId() : int
    {
        return $this->userId;
    }

    public function articleId() : int
    {
        return $this->articleId;
    }

    public function parentCommentId() : ?int
    {
        return $this->parentCommentId;
    }

    public function title() : string
    {
        return $this->title;
    }

    public function body() : string
    {
        return $this->body;
    }

    public function createdAt() : DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function author() : string
    {
        return $this->author;
    }

    public function averageScore() : float
    {
        return $this->averageScore;
    }

    public function children() : array
    {
        return $this->children;
    }

    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    public function hasChildren() : bool
    {
        return ! empty($this->children);
    }
}
