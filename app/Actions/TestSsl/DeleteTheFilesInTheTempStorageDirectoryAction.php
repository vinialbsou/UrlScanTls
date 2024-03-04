<?php

namespace App\Actions\TestSsl;


use App\Exceptions\ValidatorException;
use App\Tasks\TestSsl\Filesystem\DeleteTheFilesInTheTempStorageDirectoryTask;
use Exception;
use Illuminate\Http\Request;

class DeleteTheFilesInTheTempStorageDirectoryAction
{

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function run(): mixed
    {
        try{
            return DeleteTheFilesInTheTempStorageDirectoryTask::run();
        }catch(ValidatorException $error)
        {
            return $error->getJsonResponse()->getOriginalContent();
        }

    }
}
