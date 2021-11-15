<?php

namespace App\Dto;

class ItemDto
{
    /**
     * @var string Уникальный guid товара в 1С
     */
    private string $guid;

    /**
     * @var string Артикул
     */
    private string $article;

    /**
     * @var string Название
     */
    private string $name;

    /**
     * @var ItemPropertyDto[] Свойства товара
     */
    private array $itemProperty = [];

//    public function __construct(array $data)
//    {
//        $this->guid = $data['guid'];
//        $this->article = $data['article'];
//
//    }

    /**
     * @return string
     */
    public function getGuid(): string
    {
        return $this->guid;
    }

    /**
     * @param string $guid
     * @return ItemDto
     */
    public function setGuid(string $guid): ItemDto
    {
        $this->guid = $guid;
        return $this;
    }

    /**
     * @return string
     */
    public function getArticle(): string
    {
        return $this->article;
    }

    /**
     * @param string $article
     * @return ItemDto
     */
    public function setArticle(string $article): ItemDto
    {
        $this->article = $article;
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
     * @return ItemDto
     */
    public function setName(string $name): ItemDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ItemPropertyDto[]
     */
    public function getItemProperty(): array
    {
        return $this->itemProperty;
    }

    /**
     * @param ItemPropertyDto[] $itemProperty
     * @return ItemDto
     */
    public function setItemProperty(array $itemProperty): ItemDto
    {
        $this->itemProperty = $itemProperty;
        return $this;
    }

    /**
     * @param ItemPropertyDto $itemProperty
     * @param string $key
     * @return ItemDto
     */
    public function addItemProperty(ItemPropertyDto $itemProperty, string $key): itemDto
    {
        $this->itemProperty[$key] = $itemProperty;
        return $this;
    }
}
