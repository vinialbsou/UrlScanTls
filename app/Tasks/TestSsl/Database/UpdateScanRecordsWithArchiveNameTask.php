<?php

namespace App\Tasks\TestSsl\Database;

use App\Models\ScanInformationModel;
use Exception;
use Log;

class UpdateScanRecordsWithArchiveNameTask
{
    /**
     * @throws Exception
     */
    public static function run($filesToArchive, $archivePath): bool
    {
        // if the array is not empty - update database records
        if (!empty($filesToArchive)) {
            // Get an array of all the scan IDs from the files to archive
            $recordsToUpdate = array_unique(array_map(function ($file) {
                return basename($file, '.' . pathinfo($file, PATHINFO_EXTENSION));
            }, $filesToArchive));
            // Update database records
            if(($updatedRecordsCount = ScanInformationModel::updateArchiveNameByReportCodes($recordsToUpdate, basename($archivePath))) > 0){
                Log::info($updatedRecordsCount . ' database records updated');
                return true;
            } else {
                Log::info('Database records not updated');
                return false;
            }
        } else {
            Log::info('No database records to update');
            return false;
        }
    }
}
