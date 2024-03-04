<?php

namespace App\Actions\TestSsl;


use App\Enumerations\StatusQueue;
use App\Exceptions\ValidatorException;
use App\Helpers\ReturnResultHelper;
use App\Models\ScanInformationModel;
use App\Tasks\TestSsl\CheckIfScanExistAsPendingTask;
use App\Tasks\TestSsl\CreateNewScanTask;
use App\Tasks\TestSsl\Filesystem\GetScanFromArchiveTask;
use App\Tasks\TestSsl\GetScanHashWithSixteenCharactersTask;
use App\Tasks\TestSsl\ValidateScanRequestTask;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CreateOrGetExistScanAction
{

    /**
     * @param Request $request
     * @return mixed
     * @throws Exception
     */
    public static function run(Request $request): mixed
    {
        try {
            ValidateScanRequestTask::run($request);
        } catch (ValidatorException $exception) {
            return $exception->getJsonResponse();
        }

        $scanHash = GetScanHashWithSixteenCharactersTask::run($request);

        if (CheckIfScanExistAsPendingTask::run($request)) {
            return (new ReturnResultHelper())->run(-101, [], ['text' => ['hostname' => [config('statusCodeTranslation.-101')]], 'message:' => config('statusCodeTranslation.parsererror')]);

            // Ignoring cache, so it start a brand-new scan
        } else if ((int)$request->input('ignoreCache')) {
            // returns the reportCode
            return CreateNewScanTask::run($request);

            // Gets the last UNARCHIVED scan with the same scanHash
        } else if ($lastUnarchivedScanByScanHash = ScanInformationModel::getLastUnarchivedScanByScanHashAndStatus($scanHash, StatusQueue::Done)) {
            return (new ReturnResultHelper())->run(0, ['reportCode' => $lastUnarchivedScanByScanHash->report_code, 'text' => config('statusCodeTranslation.11')], ['message:' => config('statusCodeTranslation.success')]);

            // Gets the last ARCHIVED scan with the same scanHash
        } else if ($lastArchivedScanByScanHash = ScanInformationModel::getLastArchivedScanByScanHashAndStatus($scanHash, StatusQueue::Done)) {
            // returns the reportCode
            $archivedScan = GetScanFromArchiveTask::run($lastArchivedScanByScanHash);
            if($archivedScan instanceof JsonResponse) {
                return $archivedScan;
            } else {
                // Start and return a new scan if it is not possible to get the archived scan
                return CreateNewScanTask::run($request);
            }
        } else {
            // returns the reportCode
            return CreateNewScanTask::run($request);
        }

    }
}
