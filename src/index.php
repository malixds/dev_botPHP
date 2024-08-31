<?php

require __DIR__ . '/vendor/autoload.php';

use Db\UserRepository;
use Services\HandleCommandService;
use Services\HandleMessageService;
use TelegramBot\Api\BotApi;

$token = '7515297084:AAG5VDHe6ZKwze9LSsDuX1bYAnWwzN9Qynw';
$bot = new BotApi($token);
$userRepository = new UserRepository();

$offset = 0;
while (true) {

    $updates = $bot->getUpdates($offset);

    foreach ($updates as $update) {
        $message = $update->getMessage();
        $text = $message->getText();
        $userId = $message->getFrom()->getId();
        $chatId = $message->getChat()->getId();
        $user = $userRepository->find($userId);

        if ($user === null) {
            $user = $userRepository->createUser($userId);
        }
        if ($text === '/start') {
            $service = new HandleCommandService($userRepository);
            $service->run($userId, $user, $bot, $message);
        } else {
            $service = new HandleMessageService($userRepository);
            $service->run($bot, $user, $message);
        }

        $offset = $update->getUpdateId() + 1;
    }

    sleep(2); // Пауза между запросами
}