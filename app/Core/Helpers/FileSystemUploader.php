<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\Storage;

class FileSystemUploader implements FileUploader
{
    public function upload(string $file): array
    {
        $files = [];

        try {
            $files = json_decode(Storage::get($file), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            #TODO лог
        }

        return $files;
    }

    public function delete(string $file)
    {
        Storage::delete($file);
    }

    public function getListFileForUpload(): array
    {
        return Storage::files();
    }
}
