<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

interface BaseCallbackCommandHandler
{
    public function handler(Command $command, array $params): ServerResponse;
}
