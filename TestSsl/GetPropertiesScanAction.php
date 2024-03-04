<?php

namespace App\Actions\TestSsl;

use App\Exceptions\ValidatorException;
use App\Tasks\TestSsl\GetPropertiesSettingsTask;
use Exception;
use Illuminate\Http\JsonResponse;

class GetPropertiesScanAction
{
    /**
     * @return JsonResponse|string
     * @throws Exception
     */
    public static function run(): JsonResponse|string
    {
        try{
            return GetPropertiesSettingsTask::start();
        }catch(ValidatorException $error){
            return $error->getJsonResponse()->getOriginalContent();
        }
    }

}
