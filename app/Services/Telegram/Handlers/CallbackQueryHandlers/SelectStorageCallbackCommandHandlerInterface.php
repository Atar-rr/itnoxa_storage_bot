<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\UserSettingStorageService;
use App\Services\Telegram\TelegramDictionary;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class SelectStorageCallbackCommandHandlerInterface extends BaseCallbackCommandHandler
{
    /**
     * @param UserSettingStorageService $userSettingStorageService
     * @param BotUserCreateService $botUserCreateService
     */
    public function __construct(
        /** @var UserSettingStorageService */
        protected UserSettingStorageService $userSettingStorageService,
        /** @var BotUserCreateService */
        protected BotUserCreateService $botUserCreateService,
    ) {
        parent::__construct($botUserCreateService, $userSettingStorageService);
    }

    /**
     * @param Command $command
     * @param array $params
     * @return ServerResponse
     */
    public function handler(Command $command, array $params): ServerResponse
    {
        $callbackQuery = $command->getCallbackQuery();
        $message = $callbackQuery->getMessage();
        $userId = $message->getChat()->getId();

        $this->registrationUserIfNotExist($userId, $message->getChat()->getUsername());
        $this->userSettingStorageService->update($params, $userId);
        $inlineKeyboard = $this->userSettingStorageService->getUserStorageSettingKeyboard($userId);

        // обновляем клавиатуру пользователя
        Request::editMessageReplyMarkup([
            TelegramDictionary::CHAT_ID => $message->getChat()->getId(),
            TelegramDictionary::MESSAGE_ID => $message->getMessageId(),
            TelegramDictionary::REPLY_MARKUP => $inlineKeyboard,
        ]);

        #TODO добавить название склада
        #TODO сейчас в лоб, но по факту может случиться ошибка, нужно это учитывать
        $text = $params['action'] === 'set' ? 'добавлен' : 'удален';

        return $callbackQuery->answer([
            TelegramDictionary::TEXT => 'Склад ' . $text,
            TelegramDictionary::SHOW_ALERT => false,
            TelegramDictionary::CACHE_TIME => 8,
        ]);
    }
}
