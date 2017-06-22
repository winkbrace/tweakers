<?php declare(strict_types=1);

namespace Tweakers\Tests\Core;

use PHPUnit\Framework\TestCase;
use Tweakers\Core\Article;
use Tweakers\Core\ArticleRepository;
use Tweakers\DB\Connection;

class ArticleRepositoryTest extends TestCase
{
    /** @var ArticleRepository */
    private $repo;

    public function setUp()
    {
        $this->repo = new ArticleRepository(Connection::get());
    }

    /** @test */
    public function it_should_fetch_by_id()
    {
        $article = $this->repo->fetch(1);

        $this->assertInstanceOf(Article::class, $article);
        $this->assertSame(1, $article->id());
        $this->assertEquals('Once upon a time', $article->title());
        $this->assertEquals('Abe', $article->author());
    }

    /** @test */
    public function it_should_fetch_most_recent()
    {
        $articles = $this->repo->fetchMostRecent(3); // we only have 3 articles in the test data.

        $this->assertCount(3, $articles);
        $this->assertInstanceOf(Article::class, $articles[0]);
        $this->assertEquals('Abe', $articles[0]->author());
    }
}
