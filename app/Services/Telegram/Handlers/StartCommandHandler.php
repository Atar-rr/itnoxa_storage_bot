<?php

namespace App\Services\Telegram\Handlers;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommandHandler extends BaseCommandHandler
{
    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     * @throws \App\Exceptions\BotUserExistException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $from = $systemCommand->getMessage()->getFrom();
        $userId = $from->getId();

        $this->registrationUserIfNotExist($userId, $from->getUsername());

        return $systemCommand->replyToChat('Приветствую вас. Чтобы узнать больше введите команду /help');
    }
}
