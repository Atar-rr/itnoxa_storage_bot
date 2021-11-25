<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\UserSelectStorage
 *
 * @property $id
 * @property $bot_user_id
 * @property $storage_id
 * @property $created_at
 * @property $updated_at
 * @method transaction(\Closure $param)
 * @property-read \App\Models\BotUser $botUser
 * @method static Builder|UserSelectStorage newModelQuery()
 * @method static Builder|UserSelectStorage newQuery()
 * @method static Builder|UserSelectStorage query()
 * @method static Builder|UserSelectStorage whereBotUserId($value)
 * @method static Builder|UserSelectStorage whereCreatedAt($value)
 * @method static Builder|UserSelectStorage whereId($value)
 * @method static Builder|UserSelectStorage whereStorageId($value)
 * @method static Builder|UserSelectStorage whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class UserSelectStorage extends Model
{
    use HasFactory;

    public const
        REL_BOT_USER = 'botUser';

    public const
        COL_ID = 'id',
        COL_BOT_USER_ID = 'bot_user_id',
        COL_STORAGE_ID = 'storage_id';

    protected $fillable = [
        self::COL_BOT_USER_ID,
        self::COL_STORAGE_ID,
    ];

    /**
     * @return BelongsTo
     */
    public function botUser(): BelongsTo
    {
        return $this->belongsTo(BotUser::class);
    }

    /**
     * @param int $botUserId
     * @return UserSelectStorage|Builder
     */
    public function findByBotUserId(int $botUserId): Builder|UserSelectStorage
    {
        return $this->where(self::COL_BOT_USER_ID, $botUserId);
    }
}
