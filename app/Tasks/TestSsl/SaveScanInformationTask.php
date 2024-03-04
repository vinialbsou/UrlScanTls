<?php

namespace App\Tasks\TestSsl;

use App\Enumerations\StatusQueue;
use App\Models\ScanInformationModel;
use Carbon\Carbon;

class SaveScanInformationTask
{
    /**
     * @param $hostname
     * @param $reportCode
     * @param $optionsSetting
     * @param $priority
     * @return void
     * @throws \Exception
     */
    public static function run($hostname, $reportCode, $optionsSetting, $priority, $ignore_cache, $protocol, $port, $scanningType, $scanHash, $clientIp, $userId, $httpHost, $httpUserAgent): void
    {
        $date = new Carbon();
        $savingInformationScan = new ScanInformationModel;
        $savingInformationScan->user_id = $userId;
        $savingInformationScan->client_ip = $clientIp;
        $savingInformationScan->http_host = $httpHost;
        $savingInformationScan->http_user_agent = $httpUserAgent;
        $savingInformationScan->version_testssl = config('tlsscan.fixedSettings.activeVersion');
        $savingInformationScan->report_code = $reportCode;
        $savingInformationScan->dns_name = $hostname;
        $savingInformationScan->priority = $priority;
        $savingInformationScan->scan_hash = $scanHash;
        $savingInformationScan->status_scan = StatusQueue::Pending;
        $savingInformationScan->ignore_cache = $ignore_cache;
        $savingInformationScan->protocol = $protocol;
        $savingInformationScan->port = $port;
        $savingInformationScan->options_setting = $optionsSetting;
        $savingInformationScan->scanning_type = $scanningType;
        $savingInformationScan->created_at = $date;
        $savingInformationScan->save();
    }

}
