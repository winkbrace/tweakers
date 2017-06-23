<?php declare(strict_types=1);

namespace Tweakers\Tests\Core;

use PHPUnit\Framework\TestCase;
use Tweakers\Common\OrderDirection;
use Tweakers\Core\Comment;
use Tweakers\Core\CommentRepository;
use Tweakers\DB\Connection;

class CommentRepositoryTest extends TestCase
{
    /** @var CommentRepository */
    private $repo;

    public function setUp()
    {
        $this->repo = new CommentRepository(Connection::get());
    }

    /** @test */
    public function it_should_fetch_by_article()
    {
        $comments = $this->repo->fetchByArticleId(1);

        $this->assertCount(2, $comments); // article 1 has 2 first level comments
        $this->assertInstanceOf(Comment::class, $comments[0]);
    }

    /** @test */
    public function it_should_paginate()
    {
        // there are less than 500 comments, so the 2nd page (1) should return zero rows
        $comments = $this->repo->fetchByArticleId(1, -1, 1);

        $this->assertCount(0, $comments);
    }

    /** @test */
    public function it_should_sort_by_date()
    {
        $comments = $this->repo->fetchByArticleId(1, -1, 0, OrderDirection::ASC());

        $this->assertGreaterThanOrEqual($comments[0]->createdAt(), $comments[1]->createdAt());
        $this->assertGreaterThanOrEqual($comments[0]->id(), $comments[1]->id());

        $comments = $this->repo->fetchByArticleId(1, -1, 0, OrderDirection::DESC());

        $this->assertGreaterThanOrEqual($comments[1]->createdAt(), $comments[0]->createdAt());
        $this->assertGreaterThanOrEqual($comments[1]->id(), $comments[0]->id());
    }

    /** @test */
    public function it_should_filter_by_minimum_comment_score()
    {
        // for article 1 we have 2 comments with average score -1 and 1 comment with average score 1.

        $comments = $this->repo->fetchByArticleId(1, 1);

        $this->assertCount(1, $comments);
        $this->assertGreaterThanOrEqual(1, $comments[0]->averageScore());
    }

    /** @test */
    public function it_should_calculate_pagination_parameters()
    {
        $offset = $this->repo->calculateOffset(2, 10);

        $this->assertEquals(20, $offset);
    }

    /** @test */
    public function it_should_build_a_hierarchical_tree()
    {
        $comments = $this->repo->fetchByArticleId(1);

        // tree shape
        // 1
        //  `- 3
        // 2

        $this->assertCount(2, $comments);
        $this->assertCount(1, $comments[0]->children());
        $this->assertInstanceOf(Comment::class, $comments[0]->children()[0]);
        $this->assertEmpty($comments[1]->children());
    }
}
