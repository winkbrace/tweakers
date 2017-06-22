<?php declare(strict_types=1);

namespace Tweakers\Tests\Core;

use PHPUnit\Framework\TestCase;
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
    public function it_should_fetch_by_id()
    {
        $comment = $this->repo->fetch(1);

        $this->assertInstanceOf(Comment::class, $comment);
        $this->assertSame(1, $comment->id());
        $this->assertEquals('I disagree.', $comment->body());
    }
}
