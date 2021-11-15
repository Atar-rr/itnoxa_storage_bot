<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Storage
 *
 * @property int $id Идентификатор
 * @property string $name Название склада
 * @property string|null $created_at
 * @property string|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Storage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Storage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Storage query()
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Storage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Storage extends Model
{
    use HasFactory;

    public const
        COL_ID = 'id',
        COL_NAME = 'name';

    public const
        STORAGE_SERGIEV_POSAD = 1,
        STORAGE_NIZHNIY_NOVGOROD = 2,
        STORAGE_ORDER = 3;

    /**
     * Следует ли обрабатывать временные метки модели.
     *
     * @var bool
     */
    public $timestamps = false;
}
