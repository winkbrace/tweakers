<?php declare(strict_types=1);

namespace Tweakers\DB;

use PDO;

/**
 * This abstract class contains the default repository code
 */
abstract class Repository
{
    /** @var PDO */
    private $pdo;

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

        return $this->castIntegers($query->fetch(PDO::FETCH_NUM));
    }

    protected function castIntegers(array $row) : array
    {
        foreach ($row as $i => $value) {
            if (is_numeric($value) && ($int = (int) $value) == $value) {
                $row[$i] = $int;
            }
        }

        return $row;
    }
}
