<?php

namespace App\Console\Commands;

use App\Core\Helpers\FileUploadFactory;
use App\Dto\ItemDto;
use App\Dto\ItemPropertyDto;
use App\Dto\BalanceDto;
use App\Models\Storage;
use App\Services\UploadItemsService;
use Illuminate\Console\Command;

/**
 * Загрузка товарных остатков
 */
class UploadItems extends Command
{
    /**
     * Филды документа выгрузки из 1С
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
        'Витрина СП' => Storage::STORAGE_SERGIEV_POSAD,
        'ордерный' => Storage::STORAGE_ORDER,
        'Витрина НН' => Storage::STORAGE_NIZHNIY_NOVGOROD,
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
        ini_set('memory_limit', '300M');
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
        $from = $this->argument(self::ARGUMENT_FROM);

        $fileUploader = (new FileUploadFactory())->getFileUploader($from);
        $listFiles = $fileUploader->getListFileForUpload();

        //обходим список полученных файлов для загрузки данных
        foreach ($listFiles as $file) {
            $data = $fileUploader->upload($file);
            if (!isset($data[self::FIELD_BALANCE_IN_STORAGE])) {
                #TODO log, не валидный файл приехал
                continue;
            }

            #TODO добавить проверку наличия склада в MAP_STORAGES
            $items = $this->createItems($data[self::FIELD_BALANCE_IN_STORAGE]);

            $this->uploadItemsService->uploadItems($items);

            if ($needDelete) {
                $fileUploader->delete($file);
            }
        }
    }

    /**
     * Преобразование загруженных данных в DTO
     *
     * @param array $data
     * @return ItemDto[]
     */
    private function createItems(array $data): array
    {
        #TODO Возможно стоит прокинуть создание DTO в конструктор DTO
        /** @var ItemDto[] $items */
        $items = [];

        foreach ($data as $value) {
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
