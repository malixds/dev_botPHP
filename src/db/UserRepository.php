<?php

namespace Db;

use Models\User;
use PDOException;
use TelegramBot\Api\Exception;

final class UserRepository
{
    protected User $user;
    protected const TABLE = 'users';

    public function __construct()
    {
        $this->user = new User();
    }

    public function find(int $id): ?User
    {
        $sql = 'SELECT * FROM ' . self::TABLE . ' WHERE id = :id LIMIT 1';
        $stmt = $this->user->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $data = $stmt->fetch();
        var_dump($id);
        if (false === $data) {
            return null;
        }
        return new User($data['id'], $data['balance']);
    }

    /**
     * @throws Exception
     */
    public function createUser(int $userId): ?User
    {
        try {
            // Начинаем транзакцию
            $this->user->pdo->beginTransaction();

            $sql = 'INSERT INTO ' . self::TABLE . ' (id, balance) VALUES (:id, 0)';
            $stmt = $this->user->pdo->prepare($sql);
            $stmt->execute(['id' => $userId]);

            $user = $this->find($userId);

            // Завершаем транзакцию
            $this->user->pdo->commit();

            return $user;
        } catch (PDOException $e) {
            // В случае ошибки откатываем транзакцию
            $this->user->pdo->rollBack();
            throw new Exception("Ошибка при создании пользователя: " . $e->getMessage());
        }
    }

    public function save(User $user): void
    {
        $sql = 'UPDATE ' . $user->getTable() . ' SET balance = :balance WHERE id = :id';
        $stmt = $this->user->pdo->prepare($sql);
        $stmt->execute(['balance' => $user->balance, 'id' => $user->id]);
    }

}