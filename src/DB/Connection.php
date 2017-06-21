<?php declare(strict_types=1);

namespace Tweakers\DB;

use PDO;

/**
 * This class is responsible for managing the database connection as a singleton.
 */
class Connection
{
    /** @var PDO */
    private static $pdo = null;

    public static function get() : PDO
    {
        self::connect();

        return self::$pdo;
    }

    private static function connect()
    {
        if (! self::$pdo instanceof PDO) {
            // Normally I'd import these from previously set environment variables using the DotEnv package.
            self::$pdo = new PDO('mysql:host=127.0.0.1;port=33060;dbname=homestead', 'homestead', 'secret');
        }
    }
}
