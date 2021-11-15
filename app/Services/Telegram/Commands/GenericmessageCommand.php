<?php

//особенность работы с командами
namespace Longman\TelegramBot\Commands\UserCommands;

use App\Services\Telegram\Handlers\ItemSearchHandler;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class GenericmessageCommand extends UserCommand
{
    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
       return app(ItemSearchHandler::class)->handler($this);
    }
}
