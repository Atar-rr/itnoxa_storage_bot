<?php

namespace App\Services\Telegram;

use App\Models\BotUser;
use App\Models\Storage;
use App\Models\UserSelectStorage;
use App\Services\Telegram\Handlers\CallbackQueryHandlers\CallbackCommandFactory;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Longman\TelegramBot\Entities\InlineKeyboard;

/**
 * Управление настройками складов у пользователей бота
 */
class UserSettingStorageService
{
    public const STORAGE_ID = 'storage_id';

    /**
     * Экшены обрабатываемые в этом сервисе
     */
    public const
        ACTION_DROP = 'drop',
        ACTION_SET = 'set';

    /**
     * @param UserSelectStorage $userSelectStorage
     * @param Storage $storage
     * @param EditKeyboardService $editKeyboardService
     */
    public function __construct(
        protected  UserSelectStorage $userSelectStorage,
        protected Storage $storage,
        protected EditKeyboardService $editKeyboardService,
    ) {}

    /**
     * @param array $params
     * @param int $userId
     */#TODO рефакторинг DTO на входе
    public function update(array $params, int $userId): void
    {
        #TODO куда вынести константы? с одной стороны storage id относится к логике этого класса, а вот action и type ко всей группе Callback
        $storageId = (int)$params[self::STORAGE_ID];
        $action = $params['action'];

        try {
            Db::transaction(function () use ($storageId, $action, $userId) {
                if ($action === self::ACTION_DROP) {
                    $this->dropStorage($userId, $storageId);
                }
                if ($action === self::ACTION_SET) {
                    $this->setStorage($userId, $storageId);
                }
            },
                2
            );
        } catch (\Throwable $exception) {
            #TODO лог
        }

    }

    /**
     * @param BotUser $botUser
     */
    public function setDefaultSettingForNewUser(BotUser $botUser): void
    {
        $storages = Storage::all(Storage::COL_ID);
        $storageIds = [];

        foreach ($storages as $storage) {
            $storageIds[] = [UserSelectStorage::COL_STORAGE_ID => $storage->id];
        }

        $botUser->storages()->createMany(
            $storageIds
        );
    }

    /**
     * Получение клавиатуры с текущими настройками складов для пользователя
     *
     * @param int $userId
     * @return InlineKeyboard
     */#TODO рефакторинг DTO на входе и название, а так же ответ
    public function getUserStorageSettingKeyboard(int $userId): InlineKeyboard
    {
        $userStorageIds = array_flip($this->getUserStorageIds($userId));
        $storages = $this->storage::all();
        $inlineKeyboard = new InlineKeyboard([]);
        $smileSelect = hex2bin('E29C85');
        $smileCheckBox = hex2bin('E297BB');

        #TODO где должна быть логика сбора клавиатуры? Дело сервиса отдать склады, а что с ними делать его забота ли?
        foreach ($storages as $storage) {
            $userHasStorage = array_key_exists($storage->id, $userStorageIds);

            $inlineKeyboard->addRow([
                    TelegramDictionary::TEXT => ($userHasStorage ? $smileSelect : $smileCheckBox) . ' ' . $storage->name,
                    TelegramDictionary::CALLBACK_DATA => json_encode(
                        [
                            self::STORAGE_ID => $storage->id,
                            'action' => $userHasStorage ? self::ACTION_DROP : self::ACTION_SET,
                            'type' => CallbackCommandFactory::SELECT_STORAGE_COMMAND
                        ],
                        JSON_UNESCAPED_UNICODE
                    ),
                ]
            );
        }

        $inlineKeyboard->addRow($this->editKeyboardService->getCloseButton());

        return $inlineKeyboard;
    }

    /**
     * @param int $userId
     * @param int $storageId
     */
    private function setStorage(int $userId, int $storageId): void
    {
        $user = BotUser::whereTelegramUserId($userId)->first(BotUser::COL_ID);

        $this->userSelectStorage->storage_id = $storageId;

        $user->storages()->save($this->userSelectStorage);
    }

    /**
     * @param int $userId
     * @param int $storageId
     */
    private function dropStorage(int $userId, int $storageId): void
    {
        /** @var Collection  $userStorages */
        $userStorages = BotUser::whereTelegramUserId($userId)
            ->first(BotUser::COL_ID)
            ->storages()
            ->get();

        /** @var UserSelectStorage $userStorage */
        foreach ($userStorages as $userStorage) {
            if ($userStorage->storage_id === $storageId) {
                $userStorage->delete();

                break;
            }
        }
    }

    /**
     * @param int $userId
     * @return array
     */
    private function getUserStorageIds(int $userId): array
    {
        $userStorageIds = [];

        $userStorages = BotUser::whereTelegramUserId($userId)
            ->first(BotUser::COL_ID)
            ->storages()
            ->get(UserSelectStorage::COL_STORAGE_ID);

        foreach ($userStorages as $userStorage) {
            $userStorageIds[] = $userStorage->storage_id;
        }

        return $userStorageIds;
    }
}
