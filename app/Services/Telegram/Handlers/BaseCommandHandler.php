<?php

namespace App\Services\Telegram\Handlers;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

abstract class BaseCommandHandler
{
   abstract public function handler(Command $systemCommand): ServerResponse;
}
