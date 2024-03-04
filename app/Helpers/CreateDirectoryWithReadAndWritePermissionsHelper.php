<?php

namespace App\Helpers;

class CreateDirectoryWithReadAndWritePermissionsHelper
{
    /**
     * @param $directoryPath
     * @return bool
     */
    public static function run($directoryPath): bool
    {
        if (!is_dir($directoryPath)) {
            return mkdir($directoryPath, 0774, true);
        }
        return true;
    }
}
