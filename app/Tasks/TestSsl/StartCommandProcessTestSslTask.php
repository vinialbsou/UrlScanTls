<?php

namespace App\Tasks\TestSsl;

use App\Helpers\ReturnResultHelper;
use App\Jobs\RunScanTestSslJobs;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StartCommandProcessTestSslTask
{
    /**
     * @throws Exception
     */
    public static function run(Request $request, $command): JsonResponse
    {

        $priority = config('tlsscan.queue.prefixName') . $request->input('priority');

        $optionsSetting = $request->input('optionsSetting');
        $ignoreCache = $request->input('ignoreCache');
        $protocol = $request->input('protocol');
        $port = $request->input('port');
        $scanningType = $request->input('scanningType');
        $clientIp = $request->input("clientIp");
        $userId = $request->input("userId");
        $httpHost = $request->input("httpHost");
        $httpUserAgent = $request->input("httpUserAgent");
        $hostname = GenerateUriFromProtocolAndHostnamePortTask::run($request);
        $scanHash = GetScanHashWithSixteenCharactersTask::run($request);
        $reportCode = $command[0];
        unset($command[0]);

        // save the scan information in the database
        SaveScanInformationTask::run($hostname, $reportCode, $optionsSetting,
            $priority, $ignoreCache, $protocol, $port, $scanningType, $scanHash, $clientIp, $userId, $httpHost, $httpUserAgent);

        RunScanTestSslJobs::dispatch($command, $reportCode)->onQueue($priority);

        return (new ReturnResultHelper())->run(0, ['reportCode' => $reportCode, 'text' => config('statusCodeTranslation.11')], ['message:' => config('statusCodeTranslation.success')]);

    }
}
