<?php declare(strict_types=1);

namespace Tweakers\Tests\Core;

use Tweakers\Core\User;
use Tweakers\Core\UserRepository;
use PHPUnit\Framework\TestCase;
use Tweakers\DB\Connection;

class UserRepositoryTest extends TestCase
{
    /** @var UserRepository */
    private $repo;

    public function setUp()
    {
        $this->repo = new UserRepository(Connection::get());
    }

    /** @test */
    public function it_should_fetch_by_id()
    {
        $user = $this->repo->fetch(1);

        $this->assertInstanceOf(User::class, $user);
        $this->assertSame(1, $user->id());
        $this->assertEquals('Abe', $user->name());
    }
}
