<?php

namespace App\Tasks\TestSsl\Filesystem;

use Log;

class DeleteFilesFromLocalDiskTask
{
    /**
     * @param array $filesToDelete
     * @return bool
     */
    public static function run(array $filesToDelete): bool
    {
        // verify if all the files exists and delete them
        if (count($filesToDelete) > 0) {
            foreach ($filesToDelete as $fileToDelete) {
                if (file_exists($fileToDelete)) {
                    if (!unlink($fileToDelete)) {
                        Log::info('File not deleted from local disk');
                        return false;
                    }
                }
            }
            Log::info('Files deleted from local disk');
            return true;
        } else {
            Log::info('No files to delete from local disk');
            return false;
        }
    }
}
