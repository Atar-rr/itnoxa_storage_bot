<?php

namespace App\Dto;

class BotUserDto
{
    /**
     * @var int Идентификатор пользователя в Telegram
     */
    private int $userId;

    /**
     * @var string|null Имя пользователя в Telegram
     */
    private ?string $userName;

    /**
     * @param int $userId
     * @param string|null $userName
     */
    public function __construct(int $userId, ?string $userName)
    {
        $this->userId = $userId;
        $this->userName = $userName;
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
     * @return BotUserDto
     */
    public function setUserId(int $userId): BotUserDto
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUserName(): ?string
    {
        return $this->userName;
    }

    /**
     * @param string|null $userName
     * @return BotUserDto
     */
    public function setUserName(?string $userName): BotUserDto
    {
        $this->userName = $userName;
        return $this;
    }
}
