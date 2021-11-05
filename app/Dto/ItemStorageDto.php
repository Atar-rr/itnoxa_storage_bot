<?php

namespace App\Dto;

class ItemStorageDto
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
     * @return ItemStorageDto
     */
    public function setStorageId(int $storageId): ItemStorageDto
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
     * @return ItemStorageDto
     */
    public function setQuantity(int $quantity): ItemStorageDto
    {
        $this->quantity = $quantity;
        return $this;
    }
}
