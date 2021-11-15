<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property $id
 * @property $bot_user_id
 * @property $storage_id
 * @property $created_at
 * @property $updated_at
 * @method transaction(\Closure $param)
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
