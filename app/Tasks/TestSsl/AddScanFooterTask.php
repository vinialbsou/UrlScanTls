<?php

namespace App\Tasks\TestSsl;

use Illuminate\Support\Facades\Request;

class AddScanFooterTask
{
    public static function run($request, $scanDataResult)
    {
        if (empty($scanDataResult['data']['html'])) {
            return $scanDataResult;
        }
        $downloadLink = '<a href="' . config('tlsscan.bannerCreditsScan.url') . '" target="_new"><b>testssl.sh</b></a>';

        // get referer
        $referer = request()->headers->get('referer') ?? Request::server('HTTP_REFERER') ?? Request::header('host') ?? 'www.fairssl.net/en/scan';
        // remove https://
        $referer = str_replace('https://', '', $referer);


        // get the command used to make the scan
        $scanningInvocationCommand = $scanDataResult['data']['json']['Invocation'] ?? 'testssl.sh';
        // replace everything between --htmlfile= and .html with --htmlfile=filename.html and same for json
        $scanningInvocationCommand = preg_replace('/--htmlfile=.*?\.html/', '--html', $scanningInvocationCommand);
        $scanningInvocationCommand = preg_replace('/--jsonfile-pretty=.*?\.json/', '--json-pretty', $scanningInvocationCommand);
        // parameters to remove
        $removeParametersArray = [
            '/--connect-timeout.*? /',
            '/--openssl-timeout.*? /',
            '/--append /',
            '/--warnings=off /',
            '/--quiet /'
        ];
        // remove --connect-timeout* and --openssl-timeout* from command
        $scanningInvocationCommand = preg_replace($removeParametersArray, '', $scanningInvocationCommand);


        $opensslVersion = $scanDataResult['data']['json']['openssl'] ?? '';
        // remove everything after the second space
        $opensslVersion = preg_replace('/\sfrom.*?$/', '', $opensslVersion);


        //build a text message with the parameters received
        $footerText = "Scan created with the amazing open source SSL/TLS scanning engine " . $downloadLink . ' (' . config('tlsscan.fixedSettings.activeVersion') . " " . $opensslVersion . ") using the command:\n";
        $footerText .= "<i>" . $scanningInvocationCommand . "</i>\n";
        $footerText .= "\n";
        $footerText .= "Testssl.sh is free software. Distribution and modification under GPLv2 permitted.\n";
        $footerText .= "Usage without any warranties and at own risk. Download from " . $downloadLink . "\n";
        $footerText .= "\n";
        $footerText .= "This scan is provided free of charge. Please recommend this service to others.\n";
        $footerText .= "To allow fair usage if doing many or repeated scans run the scan locally.\n";
        $footerText .= "\n";
        $footerText .= "Report requested by " . $request->ip() . "\n";
        // Todo in order to fix this, we need the API to send the real referer, instead of using a default scan_url to decide the referer
        // If we get fairssl-api to send the real referer, we can reinstante the following, and remove the default values from referer that does not work.
        // . ' from <a href="https://' . $referer . '" target="_new"><b>' . $referer . "</b></a>\n";


        $footerTextHtml = "<span>" . $footerText . "</span>\n</pre>\n</body>";

        // add footer to finished or partial html
        if (strpos($scanDataResult['data']['html'], '</body>') !== false) {
            $scanDataResult['data']['html'] = str_replace("</pre>\n</body>", $footerTextHtml, $scanDataResult['data']['html']);
        } else {
            $scanDataResult['data']['html'] .= "\n\n\n" . $footerTextHtml;
        }


        return $scanDataResult;
    }
}
