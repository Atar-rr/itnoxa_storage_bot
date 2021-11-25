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
        $this->token = (string)env(self::TOKEN_YANDEX_DISK);
    }

    /**
     * @return array
     */
    public function getListFileForUpload(): array
    {
        $return = [];
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

        foreach ($files[self::RESPONSE_FIELD_EMBEDDED][self::RESPONSE_FIELD_ITEMS] as $file) {
            $return[] = $file[self::RESPONSE_FIELD_FILE];
            $this->filePath[$file[self::RESPONSE_FIELD_FILE]] = $file[self::RESPONSE_FIELD_PATH];
        }

        return $return;
    }

    /**
     * @param string $file
     * @return array
     */
    public function upload(string $file): array
    {
        try {
            $file = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            #TODO лог
        }

        return $file;
    }

    /**
     * @param string $file
     */
    public function delete(string $file): void
    {
        #TODO что если не вышло?
        $pendingRequest = Http::withHeaders([
            RequestHeaders::ACCEPT => 'application/json',
            RequestHeaders::AUTHORIZATION => $this->token,
        ])->bodyFormat('query');

        $result = $pendingRequest->delete(
            self::YANDEX_RESOURCE_URL,
            [
                self::REQUEST_FIELD_PATH => $this->filePath[$file],
            ]
        );

        // попробуем еще раз
        if (!$result->successful()) {
            $pendingRequest->delete(
                self::YANDEX_RESOURCE_URL,
                [
                    self::REQUEST_FIELD_PATH => $this->filePath[$file],
                ]
            );
            #TODO лог
        }
    }
}
