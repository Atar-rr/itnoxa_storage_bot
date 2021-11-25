<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use App\Dto\EditKeyboardDto;
use App\Services\Telegram\EditKeyboardService;
use App\Services\Telegram\TelegramDictionary;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;

class CloseKeyboardCallbackCommandHandler implements BaseCallbackCommandHandler
{
    protected const DEFAULT_MESSAGE = 'Настройки успешно сохранены';

    public function __construct(
        protected EditKeyboardService $editKeyboardService,
    ) {}

    public function handler(Command $command, array $params): ServerResponse
    {
        $callbackQuery = $command->getCallbackQuery();

        $this->editKeyboardService->editKeyboard(
         $this->getEditKeyboardDto($callbackQuery)
        );

        return $callbackQuery->answer([
            TelegramDictionary::TEXT => hex2bin('F09F918C') . ' '. self::DEFAULT_MESSAGE,
            TelegramDictionary::SHOW_ALERT => false,
            TelegramDictionary::CACHE_TIME => 8,
        ]);
    }

    /**
     * @param CallbackQuery $callbackQuery
     * @return EditKeyboardDto
     */
    private function getEditKeyboardDto(CallbackQuery $callbackQuery): EditKeyboardDto
    {
        $message = $callbackQuery->getMessage();

        return (new EditKeyboardDto(
            $message->getChat()->getId(),
            $message->getMessageId(),
            self::DEFAULT_MESSAGE,
        ));
    }
}
