<?php

namespace App\Tasks\TestSsl\Filesystem;

use Illuminate\Support\Facades\Storage;
use Log;

class SendFileToArchiveDiskTask
{
    public static function run($archivePath): bool
    {
        if (file_exists($archivePath)) {
            // get the filename from the archivePath and send the file to the archive disk
            if(! Storage::disk(config('tlsscan.fixedSettings.scanArchiveDisk', 's3'))->put(basename($archivePath), file_get_contents($archivePath))) {
                Log::info('Archive file not sent to the cloud');
                return false;
            } else {
                Log::info('Archive file sent to the cloud');
                return true;
            }
        } else {
            Log::info('Archive file not sent to the cloud');
            return false;
        }
    }
}
