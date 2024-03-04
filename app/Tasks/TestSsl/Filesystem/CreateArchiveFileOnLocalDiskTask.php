<?php

namespace App\Tasks\TestSsl\Filesystem;

use Illuminate\Support\Facades\Storage;
use Log;
use ZipArchive;

class CreateArchiveFileOnLocalDiskTask
{
    public static function run($filesToArchive): string
    {
        // if the array is not empty - create archive
        if (!empty($filesToArchive)) {
            $localDisk = Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'));
            // Root path for scan files
            $rootPath = $localDisk->getConfig()['root']; // path to the root of the disk
            $archiveDisk = Storage::disk(config('tlsscan.fixedSettings.scanArchiveDisk', 's3'));
            // Archive name
            $archiveName = config('tlsscan.fixedSettings.scanArchivePrefixName', 'archive_') . date('Y-m-d_H-i-s') . '.zip';
            // Archive path
            $archivePath = $rootPath . DIRECTORY_SEPARATOR . $archiveName;

            // Creates a zip file with the files that will be archived
            $zip = new ZipArchive();
            $zip->open($archivePath, ZipArchive::CREATE);
            // Add files to the archive
            foreach ($filesToArchive as $file) {
                $zip->addFile($file, basename($file));
            }
            Log::info($zip->numFiles . ' files archived');
            if ($zip->close()) {
                Log::info('Archive created successfully');
                return $archivePath;
            } else {
                Log::info('Archive creation failed');
                return '';
            }
        } else {
            Log::info('No files to archive');
            return '';
        }
    }
}
