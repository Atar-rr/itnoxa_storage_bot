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
        $botApiKey = config('telegramBot.telegram_token');
        $botUsername = config('telegramBot.telegram_name');
        $telegram = new Telegram($botApiKey, $botUsername);
        $telegram->addCommandsPath(base_path('app/Services/Telegram/Commands'));

        $telegram->useGetUpdatesWithoutDatabase();
        $telegram->handleGetUpdates();
    }
}
