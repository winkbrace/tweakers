<?php declare(strict_types=1);

namespace Tweakers\Tests\DB;

use Tweakers\DB\Connection;
use PHPUnit\Framework\TestCase;

class ConnectionTest extends TestCase
{
    /** @test */
    public function it_should_connect_to_mysql()
    {
        $pdo = Connection::get();

        $this->assertInstanceOf(\PDO::class, $pdo);
    }
}
