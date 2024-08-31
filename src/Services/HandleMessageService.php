<?php

declare(strict_types=1);

namespace Services;

use Db\UserRepository;
use Models\User;
use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class HandleMessageService
{
    public function __construct(
        private UserRepository $userRepository
    )
    {
    }

    public function run(BotApi $bot, User $user, Message $message): void
    {
        $chatId = $message->getChat()->getId();
        $text = str_replace(',', '.', $message->getText());

        if (is_numeric($text)) {
            $amount = (float)$text;
            $user->balance = $user->balance + $amount;

            if ($user->balance < 0) {
                $bot->sendMessage($chatId, "Ошибка: Недостаточно средств.");
                return;
            }

            $this->userRepository->save($user);
            $bot->sendMessage($chatId, "Ваш новый баланс: $" . number_format($user->balance, 2));

        } else {
            $bot->sendMessage($chatId, "Текст не является числом.");
        }
    }
}