<?php

namespace App\Actions\TestSsl;

use App\Exceptions\ValidatorException;
use App\Tasks\TestSsl\GetScanSslStatusTask;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetScanFileStatusAction
{
    /**
     * @param Request $request
     * @return JsonResponse|mixed
     * @throws Exception
     */
    public static function run(Request $request): mixed
    {
        try{
            return GetScanSslStatusTask::run($request);
        }catch(ValidatorException $error)
        {
            return $error->getJsonResponse()->getOriginalContent();
        }

    }
}
