<?php

namespace App\Services\Telegram\Handlers;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class GenericMessageCommandHandler extends BaseCommandHandler
{
    public function handler(Command $systemCommand): ServerResponse
    {
        // Если это ответ на прошлое сообщение, то предполагаем, что это запрос отчета
        if ($systemCommand->getMessage()->getReplyToMessage() !== null) {
            return app(LowSaleReportHandler::class)->handler($systemCommand);
        }

        return app(ItemSearchHandler::class)->handler($systemCommand);
    }
}
