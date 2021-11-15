<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\ItemStorage
 *
 * @property int $id Идентификатор
 * @property int $item_property_id Идентификатор характеристики товара
 * @property int $storage_id Идентификатор склада
 * @property int $quantity Кол-во товаров на складе
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance query()
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance whereItemPropertyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance whereStorageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ItemPropertyBalance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ItemPropertyBalance extends Model
{
    use HasFactory;

    public const
        REL_ITEM_PROPERTY = 'itemProperty',
        REL_STORAGE = 'storage';

    public const
        COL_ID = 'id',
        COL_ITEM_PROPERTY_ID = 'item_property_id',
        COL_STORAGE_ID = 'storage_id',
        COL_QUANTITY = 'quantity';

    protected $fillable = [
        'quantity',
    ];

    /**
     * @return BelongsTo
     */
    public function itemProperty(): BelongsTo
    {
        return $this->belongsTo(ItemProperty::class);
    }

    /**
     * @return BelongsTo
     */
    public function storage(): BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }
}
