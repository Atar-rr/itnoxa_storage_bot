<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use App\Exceptions\CallbackCommandNotFound;

class CallbackCommandFactory
{
    public const
        SELECT_STORAGE_COMMAND = 'select_storage_command',
        CLOSE_KEYBOARD = 'close_keyboard';

    /**
     * @throws CallbackCommandNotFound
     */
    public static function createCallbackQueryCommandHandler(string $type): BaseCallbackCommandHandler
    {
        if ($type === self::SELECT_STORAGE_COMMAND) {
            return app(SelectStorageCallbackCommandHandler::class);
        }

        if ($type === self::CLOSE_KEYBOARD) {
            return app(CloseKeyboardCallbackCommandHandler::class);
        }

        throw new CallbackCommandNotFound("Class {$type} not exists in factory upload");
    }
}
