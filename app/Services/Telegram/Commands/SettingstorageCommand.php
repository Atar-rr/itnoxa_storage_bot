<?php
//особенность работы с командами
namespace Longman\TelegramBot\Commands\UserCommands;

use App\Services\Telegram\Handlers\SettingStorageCommandHandler;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class SettingstorageCommand extends UserCommand
{
    protected $name = 'settingstorage';

    protected $description = 'Настройка складов для просмотра остатков';

    protected $usage = '/settingstorage';

    public function execute(): ServerResponse
    {
        return app(SettingStorageCommandHandler::class)->handler($this);
    }
}
