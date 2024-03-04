<?php

namespace App\Tasks\TestSsl;

use App\Enumerations\StatusQueue;
use App\Models\ScanInformationModel;
use Exception;
use Illuminate\Http\Request;

class CheckThePercentOfReportScanTask
{
    /**
     * @param Request $request
     * @param $content
     * @return void
     * @throws Exception
     */
    public static function start(Request $request, $content): int
    {

        $reportCode = $request->route('reportCode');
        $percentTitleScan = config('tlsscan.percentTitleScan');

        $percent = 0;
        $contentHtml = $content['html'];
        foreach (config('tlsscan.scanProgressSearchArray') as $searchString => $progressStatusArray) {
            if (str_contains($contentHtml, $searchString)) {
//                    ScanInformationModel::updateDateAndTimeScanned($request->input('reportCode'), $content['json']['scanTime'], StatusQueue::Done);
                    ScanInformationModel::updateScanStatus($request->input('reportCode'), StatusQueue::Done);

                $percent = 100;
            } else {
                foreach ($percentTitleScan as $keyPercent => $value) {
                    if (str_contains($contentHtml, $value)) {
                        $percent = $keyPercent;
                        break;
                    }
                }
            }
        }
        return $percent;
    }
}
