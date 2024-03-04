<?php

namespace App\Tasks\TestSsl\Filesystem;

use App\Helpers\CreateDirectoryWithReadAndWritePermissionsHelper;

class VerifyAndCreateDirectoryToSaveScanFilesTask
{
    /**
     * @return string
     * @throws \Exception
     */
    public static function run(): string
    {
        $fileScanPath = config('tlsscan.fixedSettings.pathFileScanSave');

        if (!is_dir($fileScanPath)) {
            CreateDirectoryWithReadAndWritePermissionsHelper::run($fileScanPath);
        }
        return $fileScanPath;
    }
}
