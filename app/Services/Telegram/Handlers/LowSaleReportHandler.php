<?php

namespace App\Services\Telegram\Handlers;

use App\Dto\Request\LowSalesDto;
use App\Services\BotUser\BotUserCreateService;
use App\Services\ReportsService;
use App\Services\Telegram\TelegramDictionary;
use App\Services\Telegram\UserSettingStorageService;
use Illuminate\Support\Facades\Storage;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class LowSaleReportHandler extends BaseCommandHandler
{
    /**
     * @param  BotUserCreateService       $botUserCreateService
     * @param  UserSettingStorageService  $userSettingStorageService
     * @param  ReportsService             $reportsService
     */
    public function __construct(
        protected BotUserCreateService $botUserCreateService,
        protected UserSettingStorageService $userSettingStorageService,
        protected ReportsService $reportsService
    ) {
        parent::__construct($botUserCreateService, $userSettingStorageService);
    }

    /**
     * @throws \Longman\TelegramBot\Exception\TelegramException
     * @throws \Exception
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $parts = explode(' ', $systemCommand->getMessage()->getText());

        if (count($parts) !== 2) {
            return $systemCommand->replyToChat(
                'Вы должны указать 2 даты'
            );
        }

        $data = [
            'down_date' => $parts[0],
            'up_date'   => $parts[1],
        ];

        $filename = $this->reportsService->getLowSalesReportItemsByExcel(LowSalesDto::fromArray($data));

        return Request::sendDocument(

            [
                TelegramDictionary::CHAT_ID  => $systemCommand->getMessage()->getChat()->getId(),
                TelegramDictionary::TEXT     => 'Отчет',
                TelegramDictionary::DOCUMENT => Storage::path($filename) # имя сохраненного файла
            ]
        );
    }
}
