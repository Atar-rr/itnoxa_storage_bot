<?php

namespace App\Core\Helpers;

interface FileUploader
{
    public function upload(string $file): array;
    public function delete(string $file);
    public function getListFileForUpload(): array;
}
