<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\BotUser
 *
 * @property $id
 * @property $telegram_user_id
 * @property $user_name
 * @property $created_at
 * @property $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\UserSelectStorage[] $storages
 * @property-read int|null $storages_count
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser whereTelegramUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotUser whereUserName($value)
 * @mixin \Eloquent
 */
class BotUser extends Model
{
    use HasFactory;

    public const REL_STORAGES = 'storages';

    /**
     * Справочник полей модели
     */
    public const
        COL_ID = 'id',
        COL_TELEGRAM_USER_ID = 'telegram_user_id',
        COL_USER_NAME = 'user_name';


    protected $fillable = [
        'user_name',
        'telegram_user_id',
    ];

    /**
     * @return HasMany
     */
    public function storages(): HasMany
    {
        return $this->hasMany(UserSelectStorage::class);
    }

    /**
     * Существует ли пользователь с таким telegram_user_id
     *
     * @param int $telegramUserid
     * @return bool
     */
    public function existsByTelegramUserId(int $telegramUserid): bool
    {
        return self::where(self::COL_TELEGRAM_USER_ID, $telegramUserid)->exists();
    }
}
