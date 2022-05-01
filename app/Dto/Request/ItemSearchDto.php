<?php

namespace App\Dto\Request;

class ItemSearchDto
{
    /**
     * @param string $phrase
     * @param int $userId
     */
    public function __construct(
        /** @var string Поисковая фраза */
        private string $phrase,
        /** @var int Идентификатор пользователя телеграм */
        private int $userId,
    ) {}

    /**
     * @return string
     */
    public function getPhrase(): string
    {
        return $this->phrase;
    }

    /**
     * @param string $phrase
     * @return ItemSearchDto
     */
    public function setPhrase(string $phrase): ItemSearchDto
    {
        $this->phrase = $phrase;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return ItemSearchDto
     */
    public function setUserId(int $userId): ItemSearchDto
    {
        $this->userId = $userId;
        return $this;
    }
}
