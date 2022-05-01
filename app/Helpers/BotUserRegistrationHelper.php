<?php

namespace App\Helpers;

use App\Dto\Request\BotUserDto;
use App\Exceptions\BotUserExistException;
use App\Models\BotUser;
use Illuminate\Support\Facades\DB;

trait BotUserRegistrationHelper
{
    /**
     * @param  int     $userId
     * @param  string  $userName
     *
     * @throws BotUserExistException
     */
    protected function registrationUserIfNotExist(int $userId, string $userName): void
    {
        #TODO будет дублирование в других командах..нужен хелпер
        if (!BotUser::whereTelegramUserId($userId)->exists()) {
            DB::beginTransaction();
            try {
                $botUser = $this->botUserCreateService->create(
                    new BotUserDto($userId, $userName)
                );
                $this->userSettingStorageService->setDefaultSettingForNewUser($botUser);
            } catch (BotUserExistException $e) {
                DB::rollBack();
                throw $e;
            }
            DB::commit();
        }
    }
}
