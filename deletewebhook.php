<?php
#TODO  привести в божеский вид и запушить set/delete

// Load composer
use Longman\TelegramBot\Entities\Update;

require __DIR__ . '/vendor/autoload.php';

$bot_api_key = 'your:bot_api_key';
$bot_username = 'username_bot';
$hook_url = 'https://botitnoxa.ru/api/updates/<token>';
$allowed_updates = [
     Update::TYPE_MESSAGE,
    Update::TYPE_CHANNEL_POST,
    Update::TYPE_INLINE_QUERY,
    Update::TYPE_CHOSEN_INLINE_RESULT,
    Update::TYPE_CALLBACK_QUERY,
    Update::TYPE_SHIPPING_QUERY,
    Update::TYPE_PRE_CHECKOUT_QUERY,
    Update::TYPE_POLL,
    Update::TYPE_POLL_ANSWER,
    Update::TYPE_MY_CHAT_MEMBER,
    Update::TYPE_CHAT_MEMBER,
];

try {

    $telegram = new Longman\TelegramBot\Telegram("", "");

    // Set webhook
//    $result = $telegram->deleteWebhook();
    $result = $telegram->setWebhook($hook_url, ['allowed_updates' => $allowed_updates]);
    if ($result->isOk()) {
        echo $result->getDescription();
    }
} catch (Longman\TelegramBot\Exception\TelegramException $e) {
    // log telegram errors
     echo $e->getMessage();
}

