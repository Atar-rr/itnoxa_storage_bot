<?php

namespace App\Services\Telegram\Handlers;

use App\Dto\BotUserDto;
use App\Helpers\BotUserRegistrationHelper;
use App\Models\BotUser;
use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\UserSettingStorageService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommandHandler extends BaseCommandHandler
{
    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $from = $systemCommand->getMessage()->getFrom();
        $userId = $from->getId();

        $this->registrationUserIfNotExist($userId, $from->getUsername());

        return $systemCommand->replyToChat('Приветствую вас. Чтобы узнать больше введите команду /help');
    }
}
