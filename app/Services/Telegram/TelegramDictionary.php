<?php

namespace App\Services\Telegram;

interface TelegramDictionary
{
    public const
        TEXT          = 'text',
        CALLBACK_DATA = 'callback_data',
        REPLY_MARKUP  = 'reply_markup',
        CHAT_ID       = 'chat_id',
        MESSAGE_ID    = 'message_id',
        SHOW_ALERT    = 'show_alert',
        CACHE_TIME    = 'cache_time',
        DOCUMENT      = 'document';
}
