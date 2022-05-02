<?php

namespace Longman\TelegramBot\Commands\UserCommands;

use App\Services\Telegram\Handlers\LowSaleReportCommandHandler;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class LowsaleCommand extends UserCommand
{
    protected $name = 'lowsale';

    protected $description = 'Отчет о отсутствии продаж за указанный период';

    protected $usage = '/lowsale';

    public function execute(): ServerResponse
    {
        return app(LowSaleReportCommandHandler::class)->handler($this);
    }
}