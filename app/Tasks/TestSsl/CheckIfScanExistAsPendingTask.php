<?php

namespace App\Tasks\TestSsl;

use App\Enumerations\StatusQueue;
use App\Models\ScanInformationModel;
use Illuminate\Http\Request;
use Str;

class CheckIfScanExistAsPendingTask
{
    /**
     * @param Request $request
     * @return bool
     */
    public static function run(Request $request): bool
    {
        $scanHash = GetScanHashWithSixteenCharactersTask::run($request);

        return ScanInformationModel::getCountScansByScanHashAndStatus($scanHash, StatusQueue::Pending) > 0;

    }
}
