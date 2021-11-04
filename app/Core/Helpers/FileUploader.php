<?php

namespace App\Core\Helpers;

interface FileUploader
{
    public function upload(string $file);
    public function delete(string $file);
    public function getListFileForUpload(): array;
}
