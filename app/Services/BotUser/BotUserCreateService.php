<?php

namespace App\Services\BotUser;

use App\Dto\BotUserDto;
use App\Exceptions\BotUserExistException;
use App\Models\BotUser;

class BotUserCreateService
{
    /**
     * @var BotUser
     */
    private BotUser $botUser;

    /**
     * @param BotUser $botUser
     */
    public function __construct(BotUser $botUser)
    {
        $this->botUser = $botUser;
    }

    /**
     * @param BotUserDto $botUserDto
     * @return BotUser
     * @throws BotUserExistException
     */
    public function create(BotUserDto $botUserDto): BotUser
    {
        if ($this->botUser->existsByTelegramUserId($botUserDto->getUserId())) {
            throw new BotUserExistException("Пользователь с таким id {$botUserDto->getUserId()} существует" );
            #TODO лог
        }

        $botUser = BotUser::create(
            [
                BotUser::COL_TELEGRAM_USER_ID => $botUserDto->getUserId(),
                BotUser::COL_USER_NAME => $botUserDto->getUserName(),
            ]
        );

        return $botUser->first();
    }
}
