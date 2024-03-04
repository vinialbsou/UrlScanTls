<?php

namespace App\Actions\TestSsl;


use App\Exceptions\ValidatorException;
use App\Tasks\TestSsl\Filesystem\ZipOldScanFilesAndMoveToArchiveTask;
use Exception;
use Illuminate\Http\Request;

class ZipOldScanFilesAndMoveToArchiveAction
{

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function run(): mixed
    {
        try{
            return ZipOldScanFilesAndMoveToArchiveTask::run();
        }catch(ValidatorException $error)
        {
            return $error->getJsonResponse()->getOriginalContent();
        }

    }
}
