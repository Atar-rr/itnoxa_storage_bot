<?php

namespace App\Services;

use App\Dto\Request\LowSalesDto;
use App\Export\LargeStocksExport;
use App\Export\LowSalesExport;
use App\Repository\ItemsRepository;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class ReportsService
{
    public function __construct(private ItemsRepository $itemsRepository)
    {
    }

    /**
     * @param  LowSalesDto  $lowSalesDto
     *
     * @return string - имя файла
     */
    public function getLowSalesReportItemsByExcel(LowSalesDto $lowSalesDto): string
    {
        $lowSalesItems = $this->itemsRepository->getItemsWithLowSales(
            $lowSalesDto->getDownTimeInterval()->format(DATE_ATOM),
            $lowSalesDto->getUpperTimeInterval()->format(DATE_ATOM)
        );

        $export   = new LowSalesExport($lowSalesItems);
        $fileName = Carbon::now()->toDateTimeString() . '.xlsx';

        Excel::store($export, $fileName, null, \Maatwebsite\Excel\Excel::XLSX);

        #TODO сделать возможность выгружать в pdf. Сейчас есть проблема с кириллицей. Нужно попробовать добавить переключение шрифта через
        //        $fileName2 = Carbon::now()->toDateTimeString() . '.pdf';
        //        Excel::store($export, $fileName2, null, \Maatwebsite\Excel\Excel::DOMPDF);

        return $fileName;
    }

    public function getItemsWithLargeStocksByExcel(): string
    {
        $items = $this->itemsRepository->getItemsWithLargeStocks();

        $export   = new LargeStocksExport($items);
        $fileName = Carbon::now()->toDateTimeString() . '_large_stocks' . '.xlsx';

        Excel::store($export, $fileName, null, \Maatwebsite\Excel\Excel::XLSX);

        return $fileName;
    }
}