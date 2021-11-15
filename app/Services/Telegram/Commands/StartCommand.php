<?php

//особенность работы с командами
namespace Longman\TelegramBot\Commands\UserCommands;

use App\Services\Telegram\Handlers\StartCommandHandler;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends UserCommand
{
    protected $name = 'start';

    protected $usage = '/start';

    public function execute(): ServerResponse
    {
        return app(StartCommandHandler::class)->handler($this);
    }
}
