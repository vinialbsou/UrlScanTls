<?php

namespace App\Tasks\TestSsl;

use App\Helpers\ReturnResultHelper;
use App\Validators\TestSsl\GetScanStatusValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class GetScanSslStatusTask
{

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public static function run(Request $request): JsonResponse
    {
        GetScanStatusValidator::run($request);

        $fileContents = GetScanDataFilesTask::run($request);

        // Todo reuse functions that do the same thing, instead of making duplicates.
//        CheckThePercentOfReportScanTask::start($request, $fileContents['data']);
        // Change the above CheckThePercent.. to use the following identical function instead
        // GenerateStatusAndProgressFromScanDataTask::run($request, $fileContents); // if no data available, we can return to client status

        $reportCode = $request->route('reportCode');
        $percentReportScan = CheckThePercentOfReportScanTask::start($request, $fileContents['data']);

        return (new ReturnResultHelper())->run(0, ['reportCode' => $reportCode, 'percent' => $percentReportScan], ['message' => config('statusCodeTranslation.success')]);
    }

}
