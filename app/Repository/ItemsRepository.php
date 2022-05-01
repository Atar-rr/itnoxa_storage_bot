<?php

namespace App\Repository;

use App\Models\Item;
use App\Models\ItemProperty;
use App\Models\ItemPropertyBalance;
use App\Models\Storage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Query\JoinClause;

class ItemsRepository
{
    /**
     * @param  string  $downTimeInterval
     * @param  string  $upperTimeInterval
     *
     * @return Collection
     */
    public function getItemsWithLowSales(string $downTimeInterval, string $upperTimeInterval): Collection
    {
        return Item::select(
            [
                Item::TABLE . '.' . Item::COL_ARTICLE . ' as article',
                Item::TABLE . '.' . Item::COL_NAME . ' as name',
                ItemProperty::TABLE . '.' . ItemProperty::COL_NAME . ' as property',
                Storage::TABLE . '.' . Storage::COL_NAME . ' as storage',
                ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::COL_QUANTITY . ' as quantity',
                ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::CREATED_AT . ' as created_at',
                ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::UPDATED_AT . ' as updated_at',
            ]
        )->join(ItemProperty::TABLE, ItemProperty::TABLE . '.' . ItemProperty::COL_ITEM_ID, Item::TABLE . '.' . Item::COL_ID)
                   ->join(ItemPropertyBalance::TABLE, fn(JoinClause $join) => $join
                       ->on(ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::COL_ITEM_PROPERTY_ID, ItemProperty::TABLE . '.' . ItemProperty::COL_ID)
                       ->where(ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::COL_STORAGE_ID, '!=', Storage::STORAGE_ORDER)
                   )
                   ->join(Storage::TABLE, Storage::TABLE . '.' . Storage::COL_ID, ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::COL_STORAGE_ID)
                   ->where(ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::COL_QUANTITY, '>', 1)
                   ->whereNotBetween(ItemPropertyBalance::TABLE . '.' . ItemPropertyBalance::UPDATED_AT, [
                       $downTimeInterval, $upperTimeInterval,
                   ])->orderBy(Item::TABLE . '.' . Item::COL_NAME)
                   ->get();
    }

    public function getItemsWithLargeStocks(): Collection
    {
        $q = ItemProperty::select('item_properties.item_id')
                         ->join('item_property_balances', 'item_properties.id', '=', 'item_property_balances.item_property_id')
                         ->where('item_property_balances.quantity', '>', 0)
                         ->groupBy('item_properties.item_id', 'item_properties.color')
                         ->havingRaw('SUM(item_property_balances.quantity) > ?', [10]);

        return Item::selectRaw('items.article, items.name, item_properties.name as property, SUM(item_property_balances.quantity) as sum')
                   ->join('item_properties', 'items.id', '=', 'item_properties.item_id')
                   ->join('item_property_balances', 'item_properties.id', '=', 'item_property_balances.item_property_id')
                   ->whereIn('items.id', $q)
                   ->where('item_property_balances.quantity', '>', 0)
                   ->groupBy('items.article', 'items.name', 'item_properties.name')
                   ->get();
    }
}