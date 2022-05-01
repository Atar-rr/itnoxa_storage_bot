<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\UserSettingStorageService;
use App\Services\Telegram\TelegramDictionary;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class UpdateUserStorageCallbackCommandHandler extends BaseCallbackCommandHandler
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
     * @param  Command  $command
     * @param  array    $params
     *
     * @return ServerResponse
     * @throws \App\Exceptions\BotUserExistException
     * @throws \JsonException|\Throwable
     */
    public function handler(Command $command, array $params): ServerResponse
    {
        $callbackQuery = $command->getCallbackQuery();
        $message = $callbackQuery->getMessage();
        $userId = $message->getChat()->getId();

        $this->registrationUserIfNotExist($userId, $message->getChat()->getUsername());

        // обновляем настройки
        $this->userSettingStorageService->update($params, $userId);

        // получаем новые настройки складов
        $inlineKeyboard = $this->userSettingStorageService->getUserStorageSettingKeyboard($userId);

        // обновляем клавиатуру пользователя
        Request::editMessageReplyMarkup([
            TelegramDictionary::CHAT_ID => $message->getChat()->getId(),
            TelegramDictionary::MESSAGE_ID => $message->getMessageId(),
            TelegramDictionary::REPLY_MARKUP => $inlineKeyboard,
        ]);

        #TODO добавить название склада, а не только добавлен/удален
        #TODO сейчас в лоб, но по факту может случиться ошибка, нужно это учитывать
        $text = $params['action'] === UserSettingStorageService::ACTION_SET ? 'добавлен' : 'удален';

        return $callbackQuery->answer([
            TelegramDictionary::TEXT => 'Склад ' . $text,
            TelegramDictionary::SHOW_ALERT => false,
            TelegramDictionary::CACHE_TIME => 8,
        ]);
    }
}
