<?php

namespace App\Console\Commands;

use App\Core\Helpers\FileUploadFactory;
use App\Dto\Request\BalanceDto;
use App\Dto\Request\ItemDto;
use App\Dto\Request\ItemPropertyDto;
use App\Models\Storage;
use App\Services\UploadItemsService;
use Dompdf\Dompdf;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Загрузка товарных остатков
 */
class UploadItems extends Command
{
    /**
     * Названия полей документа выгрузки из 1С
     */
    public const
        FIELD_RUS_NAME = 'Номенклатура',
        FIELD_RUS_ARTICLE = 'Артикул',
        FIELD_RUS_STORAGE = 'Склад',
        FIELD_RUS_PROPERTY = 'Характеристика',
        FIELD_RUS_QUANTITY = 'ВНаличииОстаток',
        FIELD_RUS_PROPERTY_GUID = 'ХарактеристикаGUID',
        FIELD_RUS_ITEM_GUID = 'НоменклатураGUID',
        FIELD_RUS_SIZE_SHOP = 'РазмерМагазина',
        FIELD_BALANCE_IN_STORAGE = 'ОстаткиНаСкладах';

    /**
     * Опции cli команды
     */
    public const OPTION_NEED_DELETE = 'need_delete';

    /**
     * Аргументы cli команды
     */
    public const ARGUMENT_FROM = 'from';

    #TODO Перенести в ItemStorages, по контексту больше к нему относиться (можно будет скрыть в нем логику)

    /** @var int[] Соответствие названий складов в 1С и идентификаторов в БД бота */
    public const MAP_STORAGES = [
        'Витрина СП'  => Storage::STORAGE_SERGIEV_POSAD,
        'ордерный'    => Storage::STORAGE_ORDER,
        'Витрина НН'  => Storage::STORAGE_NIZHNIY_NOVGOROD,
        'Витрина МСК' => Storage::STORAGE_MSK,
    ];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:items
                            {from : yandex_disk | file_system}
                            {--need_delete : удалить файл после загрузки}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Загрузить товарные остатки';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        protected UploadItemsService $uploadItemsService
    ) {
        parent::__construct();

        //Проблема при обработке больших файлов из-за лимита памяти на хостинге
        ini_set('memory_limit', '500M');
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {
        $needDelete = $this->option(self::OPTION_NEED_DELETE);
        $from       = $this->argument(self::ARGUMENT_FROM);

        $fileUploader = (new FileUploadFactory())->getFileUploader($from);
        $listFiles    = $fileUploader->getListFileForUpload();

        //обходим список полученных файлов для загрузки данных
        foreach ($listFiles as $file) {
            $data = $fileUploader->upload($file);
            if (!isset($data[self::FIELD_BALANCE_IN_STORAGE])) {
                Log::error('Отсутствуют обязательно поле ' . self::FIELD_BALANCE_IN_STORAGE);
                continue;
            }

            $items = $this->getAggregatedItems($data[self::FIELD_BALANCE_IN_STORAGE]);

            //освобождаем память от загруженного ранее файла
            unset($data);

            $this->uploadItemsService->uploadItems($items);

            if ($needDelete) {
                $fileUploader->delete($file);
            }
        }
    }

    /**
     * Преобразование загруженных данных в DTO
     *
     * @param  array  $data
     *
     * @return ItemDto[]
     */
    private function getAggregatedItems(array $data): array
    {
        /** @var ItemDto[] $items */
        $items = [];

        foreach ($data as $value) {
            if ($value[self::FIELD_RUS_PROPERTY_GUID] === '' || $value[self::FIELD_RUS_PROPERTY_GUID] === '00000000-0000-0000-0000-000000000000') {
                Log::error(implode(' ', $value) . ' Отсутствуют обязательно поле ' . self::FIELD_RUS_PROPERTY_GUID);
                continue;
            }

            if (!isset(self::MAP_STORAGES[$value[self::FIELD_RUS_STORAGE]])) {
                Log::error('Склад который не доступен для работы ' . $value[self::FIELD_RUS_STORAGE]);
                continue;
            }

            //создаем объект товара, если его еще нет в массиве
            if (!isset($items[$value[self::FIELD_RUS_ITEM_GUID]])) {
                $items[$value[self::FIELD_RUS_ITEM_GUID]] =
                    (new ItemDto())
                        ->setGuid($value[self::FIELD_RUS_ITEM_GUID])
                        ->setArticle($value[self::FIELD_RUS_ARTICLE])
                        ->setName($value[self::FIELD_RUS_NAME]);
            }

            $itemsProperty = $items[$value[self::FIELD_RUS_ITEM_GUID]]->getItemProperty();

            //создаем объект характеристики товара, если его еще нет в массиве свойств
            if (!isset($itemsProperty[$value[self::FIELD_RUS_PROPERTY_GUID]])) {
                $items[$value[self::FIELD_RUS_ITEM_GUID]]
                    ->addItemProperty(
                        (new ItemPropertyDto())
                            ->setGuid($value[self::FIELD_RUS_PROPERTY_GUID])
                            ->setName($value[self::FIELD_RUS_PROPERTY])
                            ->addItemStorage(
                                (new BalanceDto())
                                    ->setQuantity($value[self::FIELD_RUS_QUANTITY])
                                    ->setStorageId(self::MAP_STORAGES[$value[self::FIELD_RUS_STORAGE]])
                            )
                            ->setSize($value[self::FIELD_RUS_SIZE_SHOP]),
                        $value[self::FIELD_RUS_PROPERTY_GUID]
                    );

                continue;
            }

            // добавляем остаток на складе для характеристики товара
            $itemsProperty[$value[self::FIELD_RUS_PROPERTY_GUID]]
                ->addItemStorage(
                    (new BalanceDto())
                        ->setQuantity($value[self::FIELD_RUS_QUANTITY])
                        ->setStorageId(self::MAP_STORAGES[$value[self::FIELD_RUS_STORAGE]])
                );
        }

        return $items;
    }
}
