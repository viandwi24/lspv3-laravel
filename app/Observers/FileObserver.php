<?php

namespace App\Observers;

use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileObserver
{
    public function deleted(File $file)
    {
        $file = $file;
        $upload_path = 'users/files';
        $store = $file->path;
        $path = $upload_path . DIRECTORY_SEPARATOR . $store;
        $delete = Storage::delete($path);
        app('log')->info("[file:delete][{$path}]");
    }
}
