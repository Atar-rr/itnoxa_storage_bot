<?php

namespace App\Services;

use App\Dto\ItemDto;
use App\Models\Item;
use App\Models\ItemProperty;
use App\Models\ItemPropertyBalance;
use Illuminate\Database\Eloquent\Collection;

#TODO рефакторинг создания и обновления записей
#Может происходить падение по памяти из-за слишком большого кол-ва загруженных моделей и объектов DTO при загрузке полной выгрузки
class UploadItemsService
{
    /**
     * @param ItemDto[] $itemDto
     */
    public function uploadItems(array $itemDto): void
    {
        $guids = array_keys($itemDto);

        #TODO вынести в модель ?
        $items = Item::whereIn(Item::COL_GUID, $guids)->with('properties.balance')->get();

        $itemCreatedGuids = array_diff($guids, array_column($items->toArray(), Item::COL_GUID));

        // создаем если есть новые товары
        if (count($itemCreatedGuids) > 0) {
            $this->createItems(
                $itemCreatedGuids,
                $itemDto
            );
        }

        // обновляем товары
        $this->updateItems($items, $itemDto);
    }

    /**
     * @param array $itemGuids
     * @param ItemDto[] $itemDto
     */
    private function createItems(array $itemGuids, array $itemDto): void
    {
        $newItems = [];
        $newItemProperties = [];
        $itemPropertyDto = [];
        $newItemPropertyBalances = [];

        foreach ($itemGuids as $itemGuid) {
            $newItems[] = [
                Item::COL_NAME => $itemDto[$itemGuid]->getName(),
                Item::COL_GUID => $itemDto[$itemGuid]->getGuid(),
                Item::COL_ARTICLE => $itemDto[$itemGuid]->getArticle(),
                Item::CREATED_AT => now(),
                Item::UPDATED_AT => now(),
            ];
        }

        Item::insert($newItems);
        $items = Item::whereIn(Item::COL_GUID, $itemGuids)->get([Item::COL_ID, Item::COL_GUID])->toArray();
        $items = array_column($items, Item::COL_ID, Item::COL_GUID);

        foreach ($itemGuids as $itemGuid) {
            foreach ($itemDto[$itemGuid]->getItemProperty() as $itemProperty) {
                $itemPropertyGuid = $itemProperty->getGuid();
                $itemPropertyDto[$itemPropertyGuid] = $itemProperty;

                $newItemProperties[] = [
                    ItemProperty::COL_NAME => $itemProperty->getName(),
                    ItemProperty::COL_GUID => $itemPropertyGuid,
                    ItemProperty::COL_ITEM_ID => $items[$itemGuid],
                    ItemProperty::COL_SIZE => $itemProperty->getSize(),
                    ItemProperty::CREATED_AT => now(),
                    ItemProperty::UPDATED_AT => now(),
                ];
            }
        }

        ItemProperty::insert($newItemProperties);

        $itemPropertyGuids = array_keys($itemPropertyDto);
        $itemProperties = ItemProperty::whereIn(ItemProperty::COL_GUID, $itemPropertyGuids)
            ->get([ItemProperty::COL_ID, ItemProperty::COL_GUID])
            ->toArray();

        $itemProperties = array_column($itemProperties, ItemProperty::COL_ID, ItemProperty::COL_GUID);

        foreach ($itemPropertyGuids as $itemPropertyGuid) {
            foreach ($itemPropertyDto[$itemPropertyGuid]->getBalances() as $balanceDto) {
                $newItemPropertyBalances[] = [
                    ItemPropertyBalance::COL_ITEM_PROPERTY_ID => $itemProperties[$itemPropertyGuid],
                    ItemPropertyBalance::COL_STORAGE_ID => $balanceDto->getStorageId(),
                    ItemPropertyBalance::COL_QUANTITY => $balanceDto->getQuantity(),
                    ItemPropertyBalance::CREATED_AT => now(),
                    ItemPropertyBalance::UPDATED_AT => now(),
                ];
            }
        }

        ItemPropertyBalance::insert($newItemPropertyBalances);
    }

    /**
     * @param Collection $items
     * @param ItemDto[] $itemDto
     */
    private function updateItems(Collection $items, array $itemDto): void
    {
        foreach ($items as $item)
        {
            //обновляем товары
            $item->update(
                [
                    Item::COL_ARTICLE => $itemDto[$item->guid]->getArticle(),
                    Item::COL_NAME => $itemDto[$item->guid]->getName()
                ]
            );

            foreach ($itemDto[$item->guid]->getItemProperty() as $itemPropertyDto) {

                //обновляем свойства товаров или создаем новые
                /** @var ItemProperty $itemProperty */
                $itemProperty = $item->properties()->updateOrCreate(
                    [
                        ItemProperty::COL_GUID => $itemPropertyDto->getGuid(),
                    ],
                    [
                        ItemProperty::COL_NAME => $itemPropertyDto->getName(),
                        ItemProperty::COL_SIZE => $itemPropertyDto->getSize(),
                    ]
                );

                foreach ($itemPropertyDto->getBalances() as $balanceDto) {
                    //обновляем остаток на складе или создаем новый
                    $itemProperty->balance()->updateOrCreate(
                        [
                            ItemPropertyBalance::COL_STORAGE_ID => $balanceDto->getStorageId(),
                        ],
                        [
                            ItemPropertyBalance::COL_STORAGE_ID => $balanceDto->getStorageId(),
                            ItemPropertyBalance::COL_QUANTITY => $balanceDto->getQuantity(),
                        ]
                    );
                }
            }
        }
    }
}
