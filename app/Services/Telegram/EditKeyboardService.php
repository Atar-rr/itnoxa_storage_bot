<?php

namespace App\Services\Telegram;

use App\Dto\Request\EditKeyboardDto;
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
        // Заменить сообщение перед клавиатурой
        Request::editMessageText([
            TelegramDictionary::CHAT_ID => $editKeyboardDto->getChatId(),
            TelegramDictionary::MESSAGE_ID => $editKeyboardDto->getMessageId(),
            TelegramDictionary::TEXT => $editKeyboardDto->getText(),
        ]);

        // удалить клавиатуру
        Request::editMessageReplyMarkup([
            TelegramDictionary::CHAT_ID => $editKeyboardDto->getChatId(),
            TelegramDictionary::MESSAGE_ID => $editKeyboardDto->getMessageId(),
            TelegramDictionary::REPLY_MARKUP => $editKeyboardDto->getReplyMarkup() ?? '',
        ]);
    }

    /**
     * @return string[]
     * @throws \JsonException
     */
    public function getCloseButton(string $text = 'Завершить'): array
    {
        return [
            TelegramDictionary::TEXT => $text,
            TelegramDictionary::CALLBACK_DATA =>
                json_encode([
                    'action' => self::ACTION_CLOSE_SETTING,
                    'type'   => CallbackCommandFactory::CLOSE_KEYBOARD
                ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE),
        ];
    }
}
