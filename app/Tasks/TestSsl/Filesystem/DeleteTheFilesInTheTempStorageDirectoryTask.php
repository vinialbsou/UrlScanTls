<?php

namespace App\Tasks\TestSsl\Filesystem;

use App\Tasks\TestSsl\Database\UpdateScanRecordsWithArchiveNameTask;
use Exception;

class DeleteTheFilesInTheTempStorageDirectoryTask
{
    /**
     * @return bool
     * @throws Exception
     */
    public static function run(): bool
    {
        // Get all the files from the temp storage directory
        $filesToDelete = GetListOfAllFilesInTheTemporaryDirectoryTask::run();

        if(DeleteFilesFromLocalDiskTask::run($filesToDelete)) {
            return true;
        }

        return false;
    }
}
