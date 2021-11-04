<?php

namespace App\Core\Helpers;

abstract class FileUploadCreator
{
    abstract public function getLoader(): FileUploader;
}
