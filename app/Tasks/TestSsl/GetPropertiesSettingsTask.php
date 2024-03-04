<?php

namespace App\Tasks\TestSsl;

use App\Helpers\ReturnResultHelper;
use Illuminate\Http\JsonResponse;

class GetPropertiesSettingsTask
{
    /** Also add default port for each protocol
     * @return JsonResponse|string
     * @throws \Exception
     */
    public static function start(): JsonResponse|string
    {
        $propertiesFormat = [
            'protocols' => config('tlsscan.protocols'),
            //'optionalSettings' => config('tlsscan.testsslParametersOptionalForUser'),
            'formatFileSetting' => config('tlsscan.outputFormatFileSetting')
        ];

        return (new ReturnResultHelper())->run(0, $propertiesFormat, ['message' => config('statusCodeTranslation.success')]);

    }
}
