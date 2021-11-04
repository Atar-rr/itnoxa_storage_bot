<?php

namespace App\Core\Helpers;

class YandexDiskUploadCreator extends FileUploadCreator
{
    public function getLoader(): FileUploader
    {
        return new YandexDiskUploader();
    }
}
