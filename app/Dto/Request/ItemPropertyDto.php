<?php

namespace App\Dto\Request;

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
     * @var BalanceDto[] Товар на складах
     */
    private array $balances;

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
     * @return BalanceDto[]
     */
    public function getBalances(): array
    {
        return $this->balances;
    }

    /**
     * @param BalanceDto[] $balances
     * @return ItemPropertyDto
     */
    public function setBalances(array $balances): ItemPropertyDto
    {
        $this->balances = $balances;
        return $this;
    }

    /**
     * @param BalanceDto $itemStorage
     * @return ItemPropertyDto
     */
    public function addItemStorage(BalanceDto $itemStorage): ItemPropertyDto
    {
        $this->balances[] = $itemStorage;
        return $this;
    }
}
