<?php declare(strict_types=1);

namespace Tweakers\DB;

use PDO;

/**
 * This abstract class contains the default repository code
 */
abstract class Repository
{
    /** @var PDO */
    protected $pdo;

    protected $table = '';

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    protected function fetchById(int $id) : array
    {
        $sql = "select * 
                from   $this->table
                where  id = :id";

        $query = $this->pdo->prepare($sql);
        $query->execute([':id' => $id]);

        return $this->fetchRow($query);
    }

    protected function fetchRow(\PDOStatement $query) : array
    {
        $row = $query->fetch(PDO::FETCH_NUM);

        if ($row === false) {
            return [];
        }

        return $this->castNumbers($row);
    }

    private function castNumbers(array $row) : array
    {
        foreach ($row as $i => $value) {
            if (! is_numeric($value)) {
                continue;
            }

            $row[$i] = (int) $value == $value ? (int) $value : (float) $value;
        }

        return $row;
    }

    public function calculateOffset(int $page, int $size) : int
    {
        return $page * $size;
    }
}
