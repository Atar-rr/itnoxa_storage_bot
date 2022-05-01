<?php

namespace App\Export;

use Illuminate\Database\Eloquent\Collection;
use JetBrains\PhpStorm\ArrayShape;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LowSalesExport implements FromCollection, ShouldAutoSize, WithColumnWidths, WithColumnFormatting, WithHeadings
{
    protected Collection $invoices;

    public function __construct(protected Collection $lowSales)
    {
    }

    public function collection(): Collection
    {
        return $this->lowSales;
    }

    #[ArrayShape(['F' => "string", 'G' => "string"])] public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'G' => NumberFormat::FORMAT_DATE_DDMMYYYY,
        ];
    }

    #[ArrayShape(['E' => "int"])] public function columnWidths(): array
    {
        return [
            'E' => 10,
        ];
    }

    public function headings(): array
    {
        return [
            'Артикул',
            'Название',
            'Характеристика',
            'Склад',
            'Остаток',
            'Дата создания',
            'Дата последнего обновления(перемещение/продажа)',
        ];
    }

    #TODO Добавить возможность выгрузки в pdf
//    public function registerEvents(): array
//    {
//        return [
//            BeforeWriting::class => function(BeforeWriting $event)  {
//                $event->writer->getDefaultStyle()->getFont()->setName('dejavu sans');
//                $event->writer->getActiveSheet()->getPageSetup()->setFitToWidth(1);
//                $event->writer->getActiveSheet()->getPageSetup()->setFitToHeight(0);
//                $event->writer->getActiveSheet()->getPageSetup()->setScale(90);
//                $event->writer->getActiveSheet()->getPageMargins()->setRight(0.75);
//                $event->writer->getActiveSheet()->getPageMargins()->setLeft(0.75);
//                $event->writer->getActiveSheet()->getPageSetup()
//                            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
//                $event->writer->getActiveSheet()->getPageSetup()
//                            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A3);
//            },
//            BeforeSheet::class => function(BeforeSheet $event) {
//                \PhpOffice\PhpSpreadsheet\Settings::setLocale('ru_ru');
//            }
//        ];
//    }
}
