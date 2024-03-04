<?php

namespace App\Tasks\TestSsl;

use App\Enumerations\StatusQueue;
use App\Models\ScanInformationModel;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Redis;

class GenerateStatusAndProgressFromScanDataTask
{

    /**
     * @param $request
     * @param $scanData
     * @return mixed
     * @throws Exception
     */
    public static function run($request, $scanData): mixed
    {

        $scanParametersCache = ScanInformationModel::getParametersUsed($request->input('reportCode'));
        $scanData['scanReportInformation'] = [
            'reportCode' => $scanParametersCache->report_code ?? '',
            'hostname' => $scanParametersCache->dns_name ?? '',
            'protocol' => $scanParametersCache->protocol ?? '',
            'port' => $scanParametersCache->port ?? '',
            'optionsSetting' => $scanParametersCache->options_setting ?? '',
            'status' => StatusQueue::Scanning, // we are not pending, default to scanning
            'dateScan' => Carbon::parse($scanParametersCache->created_at ?? '')->format('d/m/Y H:i:s'),
            'ignoreCache' => $scanParametersCache->ignore_cache ?? '',
            'dnsName' => $scanParametersCache->dns_name ?? '',
            'scanningType' => $scanParametersCache->scanning_type ?? '',
        ];
        $scanData['scanReportInformation']['dnsName'] = str_replace([$scanData['scanReportInformation']['protocol'] . '://', ':' . $scanData['scanReportInformation']['port']],
            '', $scanData['scanReportInformation']['dnsName']);

        // default in case nothing match
        $scanData['loadingHeader'] = 'Scan is added to queue';
        $scanData['percentScan'] = '1%';
        $scanData['statusScan'] = StatusQueue::Scanning;
        if (empty($scanData['data']['html'])) {
            return $scanData;
        }

        // go through each percent search
        foreach (config('tlsscan.scanProgressSearchArray') as $searchString => $progressStatusArray) {
            if (str_contains($scanData['data']['html'], $searchString)) {
                $scanData['loadingHeader'] = $progressStatusArray[1];
                $scanData['percentScan'] = $progressStatusArray[0] . "%";
                if ($progressStatusArray[0] > 99) {
                    if ($scanData['scanReportInformation']['status'] !== StatusQueue::Done && !empty($scanData['data']['json'])) {
                        // is 100%
                        $scanData['scanReportInformation']['status'] = StatusQueue::Done;
                        $scanData['statusScan'] = $scanData['scanReportInformation']['status'];
                        //get from the json file, the time that completed the scan and save to the database
                        if (!empty($scanData['data']['json']['scanTime'])) {
//                            ScanInformationModel::updateDateAndTimeScanned($request->input('reportCode'),$scanData['data']['json']['scanTime'],StatusQueue::Done);
                            ScanInformationModel::updateScanStatus($request->input('reportCode'),StatusQueue::Done);
                        }
                    }
                } else {
                    $scanData['scanReportInformation']['status'] = $scanData['statusScan'];
                }
                break;
            }
        }
        return $scanData;
    }
}
