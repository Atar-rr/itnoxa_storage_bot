<?php

namespace App\Services\Telegram\Handlers;

use App\Helpers\BotUserRegistrationHelper;
use App\Services\BotUser\BotUserCreateService;
use App\Services\Telegram\UserSettingStorageService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

abstract class BaseCommandHandler
{
    use BotUserRegistrationHelper;

    public function __construct(
        /** @var BotUserCreateService */
        protected BotUserCreateService $botUserCreateService, #TODO нафига это тут? Зависимость во всех классах -_-
        /** @var UserSettingStorageService */
        protected UserSettingStorageService $userSettingStorageService, #TODO нафига это тут? Зависимость во всех классах -_-
    ) {}

    abstract public function handler(Command $systemCommand): ServerResponse;
}
