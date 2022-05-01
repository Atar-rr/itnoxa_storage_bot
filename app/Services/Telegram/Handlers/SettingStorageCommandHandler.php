<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\TelegramDictionary;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class SettingStorageCommandHandler extends BaseCommandHandler
{
    /**
     * @throws TelegramException
     * @throws \App\Exceptions\BotUserExistException
     * @throws \JsonException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $from   = $systemCommand->getMessage()->getFrom();
        $userId = $from->getId();

        $this->registrationUserIfNotExist($userId, $from->getUsername());

        $inlineKeyboard = $this->userSettingStorageService->getUserStorageSettingKeyboard($userId);

        return $systemCommand->replyToChat(
            hex2bin('E29A99') . ' Настройки склада:',
            [TelegramDictionary::REPLY_MARKUP => $inlineKeyboard,]
        );
    }
}
