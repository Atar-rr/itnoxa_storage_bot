<?php

//особенность работы с командами
namespace Longman\TelegramBot\Commands\UserCommands;

use App\Services\Telegram\Handlers\CallbackQueryHandlers\CallbackCommandFactory;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class CallbackqueryCommand extends UserCommand
{
    /**
     * @inheritDoc
     */
    public function execute(): ServerResponse
    {
        $callbackData = $this->getCallbackQuery()->getData();

        $params = json_decode($callbackData, true);

        #TODO что делать, если type не найден в фабрике
        return CallbackCommandFactory::createCallbackQueryCommandHandler(
            $params['type'] // Определяем кому делегировать работу
        )->handler($this, $params);
    }
}
