<?php

namespace App\Services\Telegram\Handlers;

use App\Services\Telegram\TelegramDictionary;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\Keyboard;
use Longman\TelegramBot\Entities\ServerResponse;

class LowSaleReportCommandHandler extends BaseCommandHandler
{
    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        return $systemCommand->replyToChat(
            'Введите нижнию и верхнию границу дат. Пример: 2022-01-01 2022-02-18',
            [
                TelegramDictionary::REPLY_MARKUP => Keyboard::forceReply()
            ]
        );
    }
}