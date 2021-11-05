<?php

namespace App\Dto;

class ItemPropertyDto
{
    /**
     * @var string Уникальный идентификатор характеристики товара в 1С
     */
    private string $guid;

    /**
     * @var string Название
     */
    private string $name;

    /**
     * @var string|null Размер
     */
    private ?string $size = null;

    /**
     * @var ItemStorageDto[] Товар на складах
     */
    private array $itemStorage;

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     * @return ItemPropertyDto
     */
    public function setGuid(string $guid): ItemPropertyDto
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ItemPropertyDto
     */
    public function setName(string $name): ItemPropertyDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSize(): ?string
    {
        return $this->size;
    }

    /**
     * @param string|null $size
     * @return ItemPropertyDto
     */
    public function setSize(?string $size): ItemPropertyDto
    {
        $this->size = $size;
        return $this;
    }

    /**
     * @return ItemStorageDto[]
     */
    public function getItemStorage(): array
    {
        return $this->itemStorage;
    }

    /**
     * @param ItemStorageDto[] $itemStorage
     * @return ItemPropertyDto
     */
    public function setItemStorage(array $itemStorage): ItemPropertyDto
    {
        $this->itemStorage = $itemStorage;
        return $this;
    }

    /**
     * @param ItemStorageDto $itemStorage
     * @return ItemPropertyDto
     */
    public function addItemStorage(ItemStorageDto $itemStorage): ItemPropertyDto
    {
        $this->itemStorage[] = $itemStorage;
        return $this;
    }
}
