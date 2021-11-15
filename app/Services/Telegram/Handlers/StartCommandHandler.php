<?php

namespace App\Services\Telegram\Handlers;

use App\Dto\BotUserDto;
use App\Models\BotUser;
use App\Services\BotUser\BotUserCreateService;
use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommandHandler extends BaseCommandHandler
{
    /**
     * @var BotUserCreateService
     */
    private BotUserCreateService $botUserCreateService;

    /**
     * @var BotUser
     */
    private BotUser $botUser;

    public function __construct(BotUserCreateService $botUserCreateService, BotUser $botUser)
    {
        $this->botUserCreateService = $botUserCreateService;
        $this->botUser = $botUser;
    }

    /**
     * @throws \App\Exceptions\BotUserExistException
     * @throws \Longman\TelegramBot\Exception\TelegramException
     */
    public function handler(Command $systemCommand): ServerResponse
    {
        $from = $systemCommand->getMessage()->getFrom();
        $userId = $from->getId();

        if (!$this->botUser->existsByTelegramUserId($userId)) {
            $this->botUserCreateService->create(
                new BotUserDto($userId, $from->getUsername())
            );
            #TODO выставить по дефолту все склады в отдельном сервисе
        }

        return $systemCommand->replyToChat('Приветствую вас. Чтобы узнать больше введите команду /help');
    }
}
