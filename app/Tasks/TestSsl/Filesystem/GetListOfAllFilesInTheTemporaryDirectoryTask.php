<?php

namespace App\Tasks\TestSsl\Filesystem;

use App\Helpers\GetFileExtension;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Log;

class GetListOfAllFilesInTheTemporaryDirectoryTask
{
    public static function run(): array
    {
        $temporaryFilesFromLocalDisk = array();
        // Loop through all files
        foreach (Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'))->files(config('tlsscan.fixedSettings.scanTempDirRelativeToStorage')) as $fileName) {
            // Get file extension
            $fileExtension = GetFileExtension::run($fileName);
            $filePath = Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'))->path($fileName);
            // Check if file extension is html or json
            if (filetype($filePath) == 'file' && ($fileExtension === 'html' || $fileExtension === 'json' || $fileExtension === 'zip')) {
                # Add file to archive
                $temporaryFilesFromLocalDisk[] = $filePath;
            }
        }

        Log::info('Amount of files found to delete from the temporary directory: ' . count($temporaryFilesFromLocalDisk));

        return $temporaryFilesFromLocalDisk;
    }
}
