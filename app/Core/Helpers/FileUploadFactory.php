<?php

namespace App\Core\Helpers;

use Exception;

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
            return new YandexDiskUploader();
        }

        if ($type === self::FILE_SYSTEM) {
            return new FileSystemUploader();
        }

        throw new Exception('Class not exists in factory upload');
    }
}
