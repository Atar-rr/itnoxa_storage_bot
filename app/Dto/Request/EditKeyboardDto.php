<?php

namespace App\Dto\Request;

use Longman\TelegramBot\Entities\InlineKeyboard;

class EditKeyboardDto
{
    public function __construct(
        /** @var int Идентификатор чата */
        private int $chatId,
        /** @var int Идентификатор сообщения */
        private int $messageId,
        /** @var string Текст заголовка клавиатуры */
        private string $text,
        /** @var string Встроенная клавиатура. Если передать null, то текущая клавиатура будет удалена */
        private ?InlineKeyboard $replyMarkup = null,
    ) {}

    /**
     * @return int
     */
    public function getChatId(): int
    {
        return $this->chatId;
    }

    /**
     * @param int $chatId
     * @return EditKeyboardDto
     */
    public function setChatId(int $chatId): EditKeyboardDto
    {
        $this->chatId = $chatId;
        return $this;
    }

    /**
     * @return int
     */
    public function getMessageId(): int
    {
        return $this->messageId;
    }

    /**
     * @param int $messageId
     * @return EditKeyboardDto
     */
    public function setMessageId(int $messageId): EditKeyboardDto
    {
        $this->messageId = $messageId;
        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     * @return EditKeyboardDto
     */
    public function setText(string $text): EditKeyboardDto
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getReplyMarkup(): ?string
    {
        return $this->replyMarkup;
    }

    /**
     * @param string|null $replyMarkup
     * @return EditKeyboardDto
     */
    public function setReplyMarkup(?string $replyMarkup): EditKeyboardDto
    {
        $this->replyMarkup = $replyMarkup;
        return $this;
    }
}
