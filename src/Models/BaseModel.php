<?php

declare(strict_types=1);

namespace Models;

use Db\Db;

class BaseModel
{
    protected $table = '';

    public \PDO $pdo;
    public function __construct()
    {
        $this->pdo = Db::getConnection();
    }

    public function getTable()
    {
        return $this->table;
    }
}