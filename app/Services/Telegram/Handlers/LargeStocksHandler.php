<?php

namespace App\Services\Telegram\Handlers;

use App\Services\BotUser\BotUserCreateService;
use App\Services\ReportsService;
use App\Services\Telegram\TelegramDictionary;
use App\Services\Telegram\UserSettingStorageService;
use Illuminate\Support\Facades\Storage;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class LargeStocksHandler extends BaseCommandHandler
{
    public function __construct(
        protected BotUserCreateService $botUserCreateService,
        protected UserSettingStorageService $userSettingStorageService,
        protected ReportsService $reportsService
    ) {
        parent::__construct($botUserCreateService, $userSettingStorageService);
    }


    public function handler(Command $systemCommand): ServerResponse
    {
        $filename = $this->reportsService->getItemsWithLargeStocksByExcel();

        return Request::sendDocument(

            [
                TelegramDictionary::CHAT_ID  => $systemCommand->getMessage()->getChat()->getId(),
                TelegramDictionary::TEXT     => 'Отчет',
                TelegramDictionary::DOCUMENT => Storage::path($filename) # имя сохраненного файла
            ]
        );
    }
}