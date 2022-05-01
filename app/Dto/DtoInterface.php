<?php

namespace App\Dto;

interface DtoInterface
{
    public static function fromArray(array $data): DtoInterface;
}
