<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use Longman\TelegramBot\Telegram;

class TelegramController extends Controller
{
    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function updates(): void
    {
        #TODO рефакторинг
        $botApiKey = config('telegramBot.telegram_token');
        $botUsername = config('telegramBot.telegram_name');

        $telegram = new Telegram($botApiKey, $botUsername);
        $telegram->addCommandsPath(base_path('app/Services/Telegram/Commands'));

        #TODO убрать куда-то для локального тестирования, запуск через getUpdates
        $telegram->useGetUpdatesWithoutDatabase();
        $telegram->handleGetUpdates();

//        $telegram->handle();
    }
}
