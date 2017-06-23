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
            try {
                self::connectFromHost();
            } catch (\PDOException $e) {
                self::connectFromServer();
            }
        }
    }

    /**
     * Required for running the unit tests on my host machine
     */
    private static function connectFromHost() : void
    {
        // Normally I'd import these from previously set environment variables using the DotEnv package.
        self::$pdo = new PDO('mysql:host=127.0.0.1;port=33060;dbname=homestead', 'homestead', 'secret');
    }

    /**
     * Required for serving the website from vagrant
     */
    private static function connectFromServer() : void
    {
        self::$pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=homestead', 'homestead', 'secret');
    }
}
