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
     * @throws \App\Exceptions\CallbackCommandNotFound|\JsonException
     */
    public function execute(): ServerResponse
    {
        $callbackData = $this->getCallbackQuery()->getData();

        $params = json_decode($callbackData, true, 512, JSON_THROW_ON_ERROR);

        return CallbackCommandFactory::createCallbackQueryCommandHandler(
            $params['type'] // Определяем кому делегировать работу
        )->handler($this, $params);
    }
}
