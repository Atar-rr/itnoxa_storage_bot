<?php

namespace App\Service;

use App\Models\Storage;

class UploadItemsService
{
    /**
     * Названия сущностей в файле загрузки
     */
    public const
        FIELD_RUS_NAME = 'Номенклатура',
        FIELD_RUS_ARTICLE = 'Артикул',
        FIELD_RUS_STORAGE = 'Склад',
        FIELD_RUS_PROPERTY = 'Характеристика',
        FIELD_RUS_QUANTITY = 'ВНаличииОстаток',
        FIELD_RUS_GUID = 'ХарактеристикаGUID';

    /** @var int[] Соответствие названий складов в 1С и идентификаторов в БД бота */
    public const STORAGES = [
        'ITNOXA' => Storage::STORAGE_SERGIEV_POSAD,
        'ордерный' => Storage::STORAGE_ORDER,
        'Витрина НН' => Storage::STORAGE_NIZHNIY_NOVGOROD
    ];

    public function uploadItems()
    {
    }
}
