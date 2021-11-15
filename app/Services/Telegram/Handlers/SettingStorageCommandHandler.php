<?php

namespace App\Services\Telegram\Handlers;

use App\Services\BotUser\UserSettingStorageService;
use App\Services\Telegram\TelegramDictionary;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class SettingStorageCommandHandler
{
    /**
     * @param UserSettingStorageService $userSettingStorageService
     */
    public function __construct(
        protected UserSettingStorageService $userSettingStorageService
    ) {}

    /**
     * @throws TelegramException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $userId = $systemCommand->getMessage()->getFrom()->getId();
        $inlineKeyboard = $this->userSettingStorageService->getUserStorageSettingKeyboard($userId);

        return $systemCommand->replyToChat(hex2bin('E29A99') . ' Настройки склада:', [
            TelegramDictionary::REPLY_MARKUP => $inlineKeyboard,
        ]);
    }
}
