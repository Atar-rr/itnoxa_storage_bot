<?php

//особенность работы с командами
namespace Longman\TelegramBot\Commands\UserCommands;

use App\Services\Telegram\Handlers\GenericMessageCommandHandler;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class GenericmessageCommand extends UserCommand
{
    /** @inheritDoc */
    public function execute(): ServerResponse
    {
       return app(GenericMessageCommandHandler::class)->handler($this);
    }
}
