<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Item
 *
 * @property int $id Идентификатор
 * @property string $guid Уникальный guid товара в 1С
 * @property string $article Артикул товара
 * @property string $name Название товара
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static Builder|Item newModelQuery()
 * @method static Builder|Item newQuery()
 * @method static Builder|Item query()
 * @method static Builder|Item whereArticle($value)
 * @method static Builder|Item whereCreatedAt($value)
 * @method static Builder|Item whereGuid($value)
 * @method static Builder|Item whereId($value)
 * @method static Builder|Item whereName($value)
 * @method static Builder|Item whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin Builder
 */
class Item extends Model
{
    use HasFactory;

    public const
        REL_PROPERTIES = 'properties',
        REL_PROPERTY_BALANCES = 'propertyBalances';

    public const
        COL_ID = 'id',
        COL_NAME = 'name',
        COL_ARTICLE = 'article',
        COL_GUID = 'guid';

    protected $fillable = [
        'name',
        'article',
        'guid'
    ];

    /**
     * @return HasMany
     */
    public function properties(): HasMany
    {
        return $this->hasMany(ItemProperty::class);
    }

    /**
     * @return HasManyThrough
     */
    public function propertyBalances(): HasManyThrough
    {
        return $this->hasManyThrough(ItemPropertyBalance::class,ItemProperty::class);
    }
}
