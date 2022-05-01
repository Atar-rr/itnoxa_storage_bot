<?php

namespace App\Services\Telegram\Commands;

use App\Services\Telegram\Handlers\LargeStocksHandler;
use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class LargestocksCommand extends UserCommand
{
    protected $name = 'largestocks';

    protected $description = 'Отчет о скопившихся товарах';

    protected $usage = '/largestocks';

    public function execute(): ServerResponse
    {
        return app(LargeStocksHandler::class)->handler($this);
    }
}
