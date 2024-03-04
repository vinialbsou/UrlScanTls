<?php

namespace App\Tasks\TestSsl\Filesystem;

use Exception;
use Storage;

class MoveArchiveFileFromS3ToTempDirectoryTask
{
    /**
     * @throws Exception
     */
    public static function run($archiveName, $tempScanPath): bool
    {
        // Get the archive file from the S3 disk and save it to the temp directory
        $archiveDisk = Storage::disk(config('tlsscan.fixedSettings.scanArchiveDisk', 's3'));
        $storageDisk = Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'));

        if(!file_exists($tempScanPath . $archiveName)) {
            if(!Storage::disk('s3')->exists($archiveName)) {
                throw new Exception('The archive file does not exist in the S3 bucket');
            }

            $archiveContent = Storage::disk('s3')->get($archiveName);
//            $storageDisk->put($archiveName, $archiveContent);
            file_put_contents($tempScanPath . $archiveName, $archiveContent);
        }

        return true;
    }
}
