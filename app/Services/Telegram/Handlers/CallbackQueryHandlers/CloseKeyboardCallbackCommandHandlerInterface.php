<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use App\Dto\EditKeyboardDto;
use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\EditKeyboardService;
use App\Services\Telegram\TelegramDictionary;
use App\Services\Telegram\UserSettingStorageService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\ServerResponse;

class CloseKeyboardCallbackCommandHandlerInterface extends BaseCallbackCommandHandler
{
    protected const DEFAULT_MESSAGE = 'Настройки успешно сохранены';

    public function __construct(
        /** @var EditKeyboardService */
        protected EditKeyboardService $editKeyboardService,
        /** @var BotUserCreateService */
        protected BotUserCreateService $botUserCreateService,
        /** @var UserSettingStorageService */
        protected UserSettingStorageService $userSettingStorageService,
    ) {
        parent::__construct($botUserCreateService, $userSettingStorageService);
    }

    public function handler(Command $command, array $params): ServerResponse
    {

        $callbackQuery = $command->getCallbackQuery();
        $chat = $callbackQuery->getMessage()->getChat();

        $this->registrationUserIfNotExist($chat->getId(), $chat->getUsername());

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
