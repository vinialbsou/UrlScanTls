<?php

namespace App\Tasks\TestSsl;

use App\Tasks\TestSsl\Filesystem\VerifyAndCreateDirectoryToSaveScanFilesTask;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateNewScanTask
{
    /**
     * @throws Exception
     */
    public static function run(Request $request): JsonResponse
    {
        $fileScanPath = VerifyAndCreateDirectoryToSaveScanFilesTask::run();

        $command = PrepareCommandForScantestSslTask::run($request, $fileScanPath);

        return StartCommandProcessTestSslTask::run($request, $command);
    }
}
