<?php

namespace App\Core\Helpers;

use App\Core\RequestHeaders;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use JsonException;
use RuntimeException;

class YandexDiskUploader implements FileUploader
{
    public const YANDEX_RESOURCE_URL = 'https://cloud-api.yandex.net/v1/disk/resource';

    public const TOKEN_YANDEX_DISK = 'TOKEN_YANDEX_DISK';

    public const REQUEST_FIELD_PATH = 'path';

    public const
        RESPONSE_FIELD_EMBEDDED = '_embedded',
        RESPONSE_FIELD_ITEMS = 'items',
        RESPONSE_FIELD_FILE = 'file',
        RESPONSE_FIELD_PATH = 'path';

    /** @var string[] */
    public array $filePath = [];

    /**
     * @var string
     */
    private string $token;

    public function __construct()
    {
        $this->token = (string) env(self::TOKEN_YANDEX_DISK);
    }

    /**
     * @return array
     * @throws JsonException
     * @throws RuntimeException
     */
    public function getListFileForUpload(): array
    {
        $return = [];

        $response = Http::withHeaders([
            RequestHeaders::ACCEPT        => 'application/json',
            RequestHeaders::AUTHORIZATION => $this->token,
        ])->get(self::YANDEX_RESOURCE_URL, [
            self::REQUEST_FIELD_PATH => 'disk:/ВыгрузкаБот',
        ]);


        $files = json_decode($response->body(), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($files[self::RESPONSE_FIELD_EMBEDDED][self::RESPONSE_FIELD_ITEMS])) {
            throw new RuntimeException('Отсутствуют файлы для загрузки');
        }

        foreach ($files[self::RESPONSE_FIELD_EMBEDDED][self::RESPONSE_FIELD_ITEMS] as $file) {
            $return[] = $file[self::RESPONSE_FIELD_FILE];
            $this->filePath[$file[self::RESPONSE_FIELD_FILE]] = $file[self::RESPONSE_FIELD_PATH];
        }

        return $return;
    }

    /**
     * @param  string  $file
     *
     * @return array
     * @throws JsonException
     */
    public function upload(string $file): array
    {
        return json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param  string  $file
     */
    public function delete(string $file): void
    {
        $pendingRequest = Http::withHeaders([
            RequestHeaders::ACCEPT        => 'application/json',
            RequestHeaders::AUTHORIZATION => $this->token,
        ])->bodyFormat('query');

        $result = $pendingRequest->delete(
            self::YANDEX_RESOURCE_URL,
            [
                self::REQUEST_FIELD_PATH => $this->filePath[$file],
            ]
        );

        // Пробуем удалить еще раз
        if (!$result->successful()) {
            $pendingRequest->delete(
                self::YANDEX_RESOURCE_URL,
                [
                    self::REQUEST_FIELD_PATH => $this->filePath[$file],
                ]
            );

            if (!$result->successful()) {
                Log::error('Не удалось удалить файл с яндекс диска' . $this->filePath[$file]);
            }
        }
    }
}
