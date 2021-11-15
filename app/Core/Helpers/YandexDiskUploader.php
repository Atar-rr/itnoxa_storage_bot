<?php

namespace App\Core\Helpers;

use App\Core\RequestHeaders;
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

    /**
     * @var string
     */
    private string $token;

    public function __construct()
    {
        $this->token = (string)env(self::TOKEN_YANDEX_DISK);
    }

    public function getListFileForUpload(): array
    {
        $files = [];

        $response = Http::withHeaders([
            RequestHeaders::ACCEPT => 'application/json',
            RequestHeaders::AUTHORIZATION => $this->token,
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

    #TODO передавать сюда не название файла, а весь массив, инкапсулировать логику работы в этом классе, чтобы не зависеть от структуры данных
    public function upload(string $file): array
    {
        try {
            $file = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            #TODO лог
        }
        //обходим и собираем DTO

        return $file;
    }

    public function delete(string $file): void
    {
        #TODO что если не успех?
        Http::withHeaders([
            RequestHeaders::ACCEPT => 'application/json',
            RequestHeaders::AUTHORIZATION => $this->token,
        ])->delete(self::YANDEX_RESOURCE_URL, [
            #TODO заменить на путь до файла
            self::REQUEST_FIELD_PATH => 'disk:/ВыгрузкаБот',
        ]);
    }
}

/**
 * 1. Получаем список файлов для загрузки типа есть
 * 2. Обходим список файлов
 * 3. Загружаем файл
 * 4. Создаем DTO с содержимым файла
 * 5. Загружаем в БД (транзакция? если не удалось, пишем лог, пробуем еще раз)
 * 6. Удаляем загруженный файл
 * 6. Переходим к след. элементу
 */
