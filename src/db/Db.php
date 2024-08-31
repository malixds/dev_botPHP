<?php

namespace Db;

use PDO;
use PDOException;
use TelegramBot\Api\Exception;

final class Db
{

    private static ?Db $instance = null;
    private ?\PDO $pdo = null;

    /**
     * @throws Exception
     */
    public function connect(): void
    {

        $params = parse_ini_file('db.ini');
        if ($params === false) {
            throw new Exception('Something wrongs with database parameters');
        }
        $conStr = sprintf(
            "pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s",
            $params['host'],
            $params['port'],
            $params['database'],
            $params['user'],
            $params['password']
        );
        $this->pdo = new \PDO($conStr);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * @return Db
     */
    public static function getInstance(): Db
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Подключение к базе данных и возврат объекта PDO.
     * @return \PDO
     * @throws \Exception
     */
    public function getPdo(): \PDO
    {
        if ($this->pdo === null) {
            $this->connect();
        }
        return $this->pdo;
    }

    /**
     * @return PDO|null
     * @throws \Exception
     */
    public static function getConnection(): ?PDO
    {
        $db = Db::getInstance();
        $pdo = $db->getPdo();
        return $pdo;
    }



//    public static function executeDatabaseRequest(string $request, PDO $pdo) {
//        $stmt = $pdo->prepare($request);
//        $stmt->execute();
//        return $stmt->fetchAll();
//    }

    protected function __construct()
    {
    }

}