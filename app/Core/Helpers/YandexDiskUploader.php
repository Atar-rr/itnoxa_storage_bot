<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\Http;
use JsonException;

class YandexDiskUploader implements FileUploader
{
    public const YANDEX_RESOURCE_URL = 'https://cloud-api.yandex.net/v1/disk/resources';
    public const TOKEN_YANDEX_DISK = 'TOKEN_YANDEX_DISK';

    public const REQUEST_FIELD_PATH = 'path';

    public const
        RESPONSE_FIELD_EMBEDDED = '_embedded',
        RESPONSE_FIELD_ITEMS = 'items';

    public function __construct()
    {
    }

    public function getListFileForUpload(): array
    {
        $files = [];
        $token = env(self::TOKEN_YANDEX_DISK);
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => $token,
        ])->get(self::YANDEX_RESOURCE_URL, [
            self::REQUEST_FIELD_PATH => 'disk:/ВыгрузкаБот',
        ]);

        try {
            $files = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            #TODO лог
        }

        return $files[self::RESPONSE_FIELD_EMBEDDED][self::RESPONSE_FIELD_ITEMS];
    }

    public function upload(string $file)
    {
        #Возможно стоит вынести в родительский класс
        try {
            $file = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            #TODO лог
        }
        //обходим и собираем DTO

        return $response->body();
    }

    public function delete(string $file)
    {
        // TODO: Implement delete() method.
    }
}

/**
 * 1. Получаем список файлов для загрузки
 * 2. Обходим список файлов
 * 3. Загружаем файл
 * 4. Создаем DTO с содержимым файла
 * 5. Загружаем в БД (транзакция? если не удалось, пишем лог, пробуем еще раз)
 * 6. Удаляем загруженный файл
 * 6. Переходим к след. элементу
 */
