<?php

namespace App\Core\Helpers;

use Illuminate\Support\Facades\Storage;
use JsonException;

class FileSystemUploader implements FileUploader
{
    /**
     * @param  string  $file
     *
     * @return array
     * @throws JsonException
     */
    public function upload(string $file): array
    {
        return json_decode(Storage::get($file), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @param  string  $file
     *
     * @return void
     */
    public function delete(string $file): void
    {
        Storage::delete($file);
    }

    /**
     * @return array
     */
    public function getListFileForUpload(): array
    {
        return Storage::files();
    }
}
