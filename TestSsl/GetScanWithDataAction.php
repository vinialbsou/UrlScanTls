<?php

namespace App\Actions\TestSsl;

use App\Exceptions\ValidatorException;
use App\Tasks\TestSsl\AddScanFooterTask;
use App\Tasks\TestSsl\CheckIfScanExistAsPendingTask;
use App\Tasks\TestSsl\FormatScanDataToClientTask;
use App\Tasks\TestSsl\GenerateHeaderSummaryInformationFromScanDataTask;
use App\Tasks\TestSsl\GenerateStatusAndProgressFromScanDataTask;
use App\Tasks\TestSsl\GetScanDataFilesTask;
use App\Tasks\TestSsl\ValidateScanRequestForGetScanTask;
use Exception;
use Illuminate\Http\Request;

class GetScanWithDataAction
{


    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function run(Request $request): mixed
    {

        try {
            // Ensure request is valid and does not contain dangerous content we could get hacked by, throw exception return
            ValidateScanRequestForGetScanTask::run($request);

            // Default scan status
            $scanData = [
                'statusScan' => 'pending',
                'percentScan' => '0%',
                'loadingHeader' => config('tlsscan.scanProgressSearchArray.Default.1'),
                'html' => null,
                'json' => null
            ];
            // if redis says scan is not started, throw exception return
            if (!CheckIfScanExistAsPendingTask::run($request)) {
                // Scan is running or complete, process the data

                $scanData = GetScanDataFilesTask::run($request);

                $scanData = GenerateHeaderSummaryInformationFromScanDataTask::run($request, $scanData); // only possible if data exist, needs data to generate...

                $scanData = AddScanFooterTask::run($request, $scanData);

                $scanData = GenerateStatusAndProgressFromScanDataTask::run($request, $scanData); // if no data available, we can return to client status
            }

            // Easy way to debug while editing html
            // echo $scanData['data']['html'];exit;

            // return scanData to client requested
            return FormatScanDataToClientTask::run($request, $scanData, $request->input('outputFormat', 'html-json')); // request, data, withDataToClient

        } catch (ValidatorException $error) {
            return $error->getJsonResponse()->getOriginalContent();
        }
    }
}
