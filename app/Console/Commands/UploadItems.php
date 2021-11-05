<?php

namespace App\Console\Commands;

use App\Core\Helpers\FileUploadFactory;
use App\Dto\ItemDto;
use App\Dto\ItemPropertyDto;
use App\Dto\ItemStorageDto;
use App\Models\Storage;
use Illuminate\Console\Command;

/**
 * Загрузка товарных остатков
 */
class UploadItems extends Command
{
    public const
        FIELD_RUS_NAME = 'Номенклатура',
        FIELD_RUS_ARTICLE = 'Артикул',
        FIELD_RUS_STORAGE = 'Склад',
        FIELD_RUS_PROPERTY = 'Характеристика',
        FIELD_RUS_QUANTITY = 'ВНаличииОстаток',
        FIELD_RUS_PROPERTY_GUID = 'ХарактеристикаGUID',
        FIELD_RUS_ITEM_GUID = 'НоменклатураGUID';

    public const MAP_STORAGES = [
        'ITNOXA' => Storage::STORAGE_SERGIEV_POSAD,
        'ордерный' => Storage::STORAGE_ORDER,
        'Склад НН' => Storage::STORAGE_NIZHNIY_NOVGOROD,
    ];


    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'upload:items';

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
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        //Проблема при обработке больших файлов из-за лимита памяти на хостинге
        ini_set('memory_limit', '300M');

        #TODO заюзать простую фабрику,вместе с фабричным методом
//        $listFiles = (new YandexDiskUploadFactory())->getLoader()->getListFileForUpload();
        $fileUploader = (new FileUploadFactory())
            ->getFileUploader(FileUploadFactory::YANDEX_DISK);
        $listFiles = $fileUploader->getListFileForUpload();

        foreach ($listFiles as $file) {
            #тут данные
            $data = $fileUploader->upload($file);
            if (!isset($data['ОстаткиНаСкладах'])) {
                continue;
            }
            #TODO добавить проверку наличия склада в БД

            #TODO Возможно стоит прокинуть создание в DTO конструктор или сделать приватные метод в этой команде
            foreach ($data['ОстаткиНаСкладах'] as $value) {
                /** @var ItemDto[] $items */
                if (!isset($items[$value[self::FIELD_RUS_ITEM_GUID]])) {
                    $items[$value[self::FIELD_RUS_ITEM_GUID]] =
                        (new ItemDto())
                            ->setGuid($value[self::FIELD_RUS_ITEM_GUID])
                            ->setArticle($value[self::FIELD_RUS_ARTICLE])
                            ->setName($value[self::FIELD_RUS_NAME]);
                }

                $items[$value[self::FIELD_RUS_ITEM_GUID]]
                    ->addItemProperty(
                        (new ItemPropertyDto())
                            ->setGuid($value[self::FIELD_RUS_PROPERTY_GUID])
                            ->setName($value[self::FIELD_RUS_PROPERTY])
                            ->addItemStorage(
                                (new ItemStorageDto())
                                    ->setQuantity($value[self::FIELD_RUS_QUANTITY])
                                    ->setStorageId($value[self::FIELD_RUS_STORAGE])
                            )
                            #TODO поправить когда размер будет в выгрузке
                            ->setSize(null)
                    );
            }

            $fileUploader->delete($file);
        }

        return 0;
    }
}
