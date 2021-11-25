<?php

namespace App\Services\Telegram\Handlers\CallbackQueryHandlers;

use App\Helpers\BotUserRegistrationHelper;
use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\UserSettingStorageService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

abstract class BaseCallbackCommandHandler
{
    use BotUserRegistrationHelper;

    public function __construct(
        /** @var BotUserCreateService */
        protected BotUserCreateService $botUserCreateService,
        /** @var UserSettingStorageService */
        protected UserSettingStorageService $userSettingStorageService,
    ) {}

    abstract public function handler(Command $command, array $params): ServerResponse;
}
