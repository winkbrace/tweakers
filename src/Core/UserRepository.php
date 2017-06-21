<?php declare(strict_types=1);

namespace Tweakers\Core;

use Tweakers\DB\Repository;

class UserRepository extends Repository
{
    protected $table = 'users';

    public function fetch(int $id) : User
    {
        $row = $this->fetchById($id);

        return new User(...$row);
    }
}
