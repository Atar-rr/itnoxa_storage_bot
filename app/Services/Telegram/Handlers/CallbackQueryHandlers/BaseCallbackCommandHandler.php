<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class BaseCallbackCommandHandler implements BaseCallbackCommandHandlerInterface
{

    public function handler(Command $command, array $params): ServerResponse
    {
        // TODO: Implement handler() method.
    }
}
