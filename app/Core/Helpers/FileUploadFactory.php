<?php

namespace App\Core\Helpers;

use Exception;
use InvalidArgumentException;

class FileUploadFactory
{
    public const
        YANDEX_DISK = 'yandex_disk',
        FILE_SYSTEM = 'file_system';

    /**
     * @param string $type
     * @return FileUploader
     * @throws Exception
     */
    public function getFileUploader(string $type): FileUploader
    {
        if ($type === self::YANDEX_DISK) {
            return app(YandexDiskUploader::class);
        }

        if ($type === self::FILE_SYSTEM) {
            return app(FileSystemUploader::class);
        }

        throw new InvalidArgumentException("Class {$type} not exists in factory upload");
    }
}
