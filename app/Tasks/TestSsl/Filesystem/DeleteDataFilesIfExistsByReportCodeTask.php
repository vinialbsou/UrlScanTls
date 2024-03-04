<?php

namespace App\Tasks\TestSsl\Filesystem;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeleteDataFilesIfExistsByReportCodeTask
{
    public static function run(string $reportCode): void
    {
        if(Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'))->delete([$reportCode . '.html',$reportCode . '.json'])){
            Log::info("Files deleted: ". $reportCode . ".[html;json]");
        }
    }
}
