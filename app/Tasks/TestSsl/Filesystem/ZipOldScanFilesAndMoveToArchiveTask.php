<?php

namespace App\Tasks\TestSsl\Filesystem;

use App\Tasks\TestSsl\Database\UpdateScanRecordsWithArchiveNameTask;
use Exception;

class ZipOldScanFilesAndMoveToArchiveTask
{
    /**
     * @return bool
     * @throws Exception
     */
    public static function run(): bool
    {
        // Get all files from local disk
        $filesToArchive = GetListOfOldScanFilesFromLocalDiskTask::run();

        // return true if the array is empty
        if (empty($filesToArchive)) {
            return true;
        }

        // Create archive
        if (file_exists($archivePath = CreateArchiveFileOnLocalDiskTask::run($filesToArchive))) {
            // Send the archive file to S3
            if (SendFileToArchiveDiskTask::run($archivePath)) {
                // Update database records with the archive name
                if (UpdateScanRecordsWithArchiveNameTask::run($filesToArchive, $archivePath)) {
                    $filesToDelete = array_merge($filesToArchive, [$archivePath]);
                    // Delete the files from local disk
                    if(DeleteFilesFromLocalDiskTask::run($filesToDelete)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}
