<?php

namespace App\Services\Telegram;

use App\Dto\EditKeyboardDto;
use App\Services\Telegram\Handlers\CallbackQueryHandlers\CallbackCommandFactory;
use Longman\TelegramBot\Request;

class EditKeyboardService
{
    public const
        ACTION_CLOSE_SETTING = 'close_setting';

    /**
     * @param EditKeyboardDto $editKeyboardDto
     */
    public function editKeyboard(EditKeyboardDto $editKeyboardDto): void
    {
        Request::editMessageText([
            TelegramDictionary::CHAT_ID => $editKeyboardDto->getChatId(),
            TelegramDictionary::MESSAGE_ID => $editKeyboardDto->getMessageId(),
            TelegramDictionary::TEXT => $editKeyboardDto->getText(),
        ]);

        Request::editMessageReplyMarkup([
            TelegramDictionary::CHAT_ID => $editKeyboardDto->getChatId(),
            TelegramDictionary::MESSAGE_ID => $editKeyboardDto->getMessageId(),
            TelegramDictionary::REPLY_MARKUP => $editKeyboardDto->getReplyMarkup() ?? '',
        ]);
    }

    /**
     * @return string[]
     */
    public function getCloseButton(string $text = 'Завершить'): array
    {
        return [
            TelegramDictionary::TEXT => $text,
            TelegramDictionary::CALLBACK_DATA =>
                json_encode(
                    [
                        'action' => self::ACTION_CLOSE_SETTING,
                        'type' => CallbackCommandFactory::CLOSE_KEYBOARD
                    ],
                    JSON_UNESCAPED_UNICODE
                ),

        ];
    }
}
