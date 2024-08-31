<?php

namespace Services;

use Db\UserRepository;
use Models\User;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class HandleCommandService
{

    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function run(int $userId, User $user, BotApi $bot, Message $message)
    {
        $chatId = $message->getChat()->getId();
        if ($user === null) {
            try {
                $this->userRepository->createUser($userId);
            } catch (\TelegramBot\Api\Exception $e) {
                $bot->sendMessage($chatId, "ОШИБКА");
            }
        }
        $bot->sendMessage($chatId, "Добро пожаловать, User #" . $user->id);
    }

}