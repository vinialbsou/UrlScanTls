<?php

namespace App\Tasks\TestSsl\Filesystem;

use App\Helpers\GetFileExtension;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Log;

class GetListOfOldScanFilesFromLocalDiskTask
{
    public static function run(): array
    {
        $oldScanFilesFromLocalDisk = array();
        // Loop through all files
        foreach (Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'))->files() as $fileName) {
            // Get file extension
            $fileExtension = GetFileExtension::run($fileName);
            $filePath = Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'))->path($fileName);
            // Check if file extension is html or json
            if (filetype($filePath) == 'file' && ($fileExtension === 'html' || $fileExtension === 'json')) {
                // Get file modification time
                $lastModified = filemtime($filePath);

                // Get file age in days
                $fileAge = ((new Carbon())->getTimestamp() - $lastModified) / 86400;

                // Delete files older than 30 days
                if ($fileAge > config('tlsscan.fixedSettings.scanFilesAgeBeforeArchive', 7)) {
                    # Add file to archive
                    $oldScanFilesFromLocalDisk[] = $filePath;
                }
            }
        }

        Log::info('Amount of files found to archive: ' . count($oldScanFilesFromLocalDisk));

        return $oldScanFilesFromLocalDisk;
    }
}
