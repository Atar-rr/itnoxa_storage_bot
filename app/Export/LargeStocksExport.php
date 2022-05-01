<?php

namespace App\Export;

use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LargeStocksExport implements FromCollection, ShouldAutoSize, WithHeadings
{
    public function __construct(protected Collection $largeStocks)
    {
    }

    public function collection()
    {
        return $this->largeStocks;
    }

    public function headings(): array
    {
        return [
            'Артикул',
            'Название',
            'Характеристика',
            'Остаток',
        ];
    }
}