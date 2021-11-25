<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\ItemProperty
 *
 * @property int $id Идентификатор
 * @property int $item_id Идентификатор товара
 * @property string $guid Уникальный guid характеристика товара в 1С
 * @property string $name Название товара
 * @property string $size Размер товара
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|ItemProperty newModelQuery()
 * @method static Builder|ItemProperty newQuery()
 * @method static Builder|ItemProperty query()
 * @method static Builder|ItemProperty whereCreatedAt($value)
 * @method static Builder|ItemProperty whereGuid($value)
 * @method static Builder|ItemProperty whereId($value)
 * @method static Builder|ItemProperty whereItemId($value)
 * @method static Builder|ItemProperty whereName($value)
 * @method static Builder|ItemProperty whereSize($value)
 * @method static Builder|ItemProperty whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin Builder
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\ItemPropertyBalance[] $balance
 * @property-read int|null $balance_count
 * @property-read \App\Models\Item $item
 */
class ItemProperty extends Model
{
    use HasFactory;

    public const
        REL_ITEM = 'item',
        REL_BALANCE = 'balance';

    public const
        COL_ID = 'id',
        COL_ITEM_ID = 'item_id',
        COL_GUID = 'guid',
        COL_NAME = 'name',
        COL_SIZE = 'size';

    protected $fillable = [
        'name',
        'guid',
        'size',
    ];

    /**
     * @return BelongsTo
     */
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * @return HasMany
     */
    public function balance(): HasMany
    {
        return $this->hasMany(ItemPropertyBalance::class);
    }
}
