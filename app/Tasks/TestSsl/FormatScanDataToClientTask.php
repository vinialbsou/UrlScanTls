<?php

namespace App\Tasks\TestSsl;

use App\Enumerations\StatusQueue;
use App\Helpers\ReturnResultHelper;

class FormatScanDataToClientTask
{
    public static function run($request, $scanData, $outputFormat = 'html-json')
    {
        $parametersUsed = [
            'hostname' => $scanData['scanReportInformation']['dnsName'],
            'reportCode' => $scanData['scanReportInformation']['reportCode'],
            'optionsSetting' => $scanData['scanReportInformation']['optionsSetting'],
            'ignoreCache' => $scanData['scanReportInformation']['ignoreCache'],
            'protocol' => $scanData['scanReportInformation']['protocol'],
            'port' => $scanData['scanReportInformation']['port'],
            'dateScan' => $scanData['scanReportInformation']['dateScan'],
            'scanningType' => $scanData['scanReportInformation']['scanningType'],
        ];

        $returnDataArray = [
            'parametersUsed' => $parametersUsed,
            'loadingHeader' => $scanData['loadingHeader'] ?? 'Scan not found',
            'percentScan' => $scanData['percentScan'] ?? '0%',
            'statusScan' => $scanData['statusScan'] ?? StatusQueue::Error
        ];

        foreach (explode('-', $outputFormat) as $activeOutputFormat) {
            $returnDataArray[$activeOutputFormat] = $scanData['data'][$activeOutputFormat] ?? '';
        }
        return (new ReturnResultHelper())
            ->run(0, $returnDataArray, ['message:' => config('statusCodeTranslation.success')])
            ->getData(true);

    }
}
