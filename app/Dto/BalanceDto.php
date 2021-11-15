<?php

namespace App\Dto;
#TODO переименовать
class BalanceDto
{
    /**
     * @var int Идентификатор склада
     */
    private int $storageId;

    /**
     * @var int Количество остатков
     */
    private int $quantity;

    /**
     * @return int
     */
    public function getStorageId(): int
    {
        return $this->storageId;
    }

    /**
     * @param int $storageId
     * @return BalanceDto
     */
    public function setStorageId(int $storageId): BalanceDto
    {
        $this->storageId = $storageId;
        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return BalanceDto
     */
    public function setQuantity(int $quantity): BalanceDto
    {
        $this->quantity = $quantity;
        return $this;
    }
}
