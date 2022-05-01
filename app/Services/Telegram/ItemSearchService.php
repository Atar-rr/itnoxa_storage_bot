<?php

namespace App\Services\Telegram;

use App\Dto\Request\ItemSearchDto;
use App\Exceptions\DomainException;
use App\Models\BotUser;
use App\Models\Item;
use App\Models\ItemProperty;
use App\Models\ItemPropertyBalance;
use App\Models\UserSelectStorage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ItemSearchService
{
    #TODO кажется они должны относится к другому классу, что-то вроде DomainErrors
    public const
        ERROR_ITEM_NOT_FOUND = 'Товар не найден',
        ERROR_NO_BALANCE = 'По запрашиваемому товару нет остатков',
        ERROR_USER_DONT_HAVE_SELECT_STORAGE = 'У вас не выбран ни один склад';

    /**
     * @param ItemSearchDto $itemSearchDto
     * @return array
     * @throws DomainException
     */
    public function findByArticle(ItemSearchDto $itemSearchDto): array
    {
        #TODO попахивает дублем app/Services/Telegram/UserSettingStorageService.php:123
        $userStorages = BotUser::whereTelegramUserId($itemSearchDto->getUserId())
            ->first()
            ->storages()
            ->get(UserSelectStorage::COL_STORAGE_ID);

        if ($userStorages === null) {
            throw new DomainException(self::ERROR_USER_DONT_HAVE_SELECT_STORAGE);
        }

        $userStorageIds = [];

        /** @var UserSelectStorage[] $userStorage */
        foreach ($userStorages as $userStorage) {
            $userStorageIds[] = $userStorage->storage_id;
        }

        // Проверяем существует ли товар вообще в БД
        if (!Item::where(Item::COL_ARTICLE, $itemSearchDto->getPhrase())->exists()) {
            throw new DomainException(self::ERROR_ITEM_NOT_FOUND);
        }

        // Извлекаем если по нему есть остатки
        $item = Item::whereHas(Item::REL_PROPERTIES, static function(Builder $query) use ($userStorageIds) {
            $query->whereHas(ItemProperty::REL_BALANCE, function(Builder $query) use ($userStorageIds) {
                $query
                    ->whereIn(ItemPropertyBalance::COL_STORAGE_ID, $userStorageIds)
                    ->where(ItemPropertyBalance::COL_QUANTITY, '>', 0);
            });
        })
            ->where(Item::COL_ARTICLE, $itemSearchDto->getPhrase())
            ->with(Item::REL_PROPERTIES . '.' . ItemProperty::REL_BALANCE)
            ->first();

        #TODO а стоит ли искать по имени после этого?
        if ($item === null) {
            throw new DomainException(self::ERROR_NO_BALANCE);
        }

       return [$item, $userStorageIds];
    }

    /**
     * @param ItemSearchDto $itemSearchDto
     * @return Collection
     * @throws DomainException
     */
    public function findByName(ItemSearchDto $itemSearchDto): Collection
    {
        $itemCriteria = '';
        $nameParts = explode(' ', $itemSearchDto->getPhrase());

        // Во избежание большого кол-ва совпадений не даем искать менее чем по 2 словам
        if (count($nameParts) < 2) {
            throw new DomainException(self::ERROR_ITEM_NOT_FOUND);
        }

        foreach ($nameParts as $namePart) {
            if ($itemCriteria === '') {
                $itemCriteria = Item::where(Item::COL_NAME, 'LIKE', "%{$namePart}%");
                continue;
            }
            $itemCriteria->where(Item::COL_NAME, 'LIKE', "%{$namePart}%");
        }

        $items = $itemCriteria->get([Item::COL_ARTICLE, Item::COL_NAME]);

        if (count($items) === 0) {
            throw new DomainException(self::ERROR_ITEM_NOT_FOUND);
        }

        return $items;
    }
}
