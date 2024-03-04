<?php

namespace App\Tasks\TestSsl;

class GetFooterMessageForScanTask
{
    public static function run($dataForBanner)
    {
       //build a text message with the parameters received
        return 'Scan SSL/TLS version: ' . $dataForBanner['scanVersion'] . ' - ' .
            ' Scanned: ' . $dataForBanner['dateScan'] . ' - ' .
            'user Address: ' . $dataForBanner['ipUser'] . ' - ' .
            'Credits: ' . $dataForBanner['url'];
    }
}

