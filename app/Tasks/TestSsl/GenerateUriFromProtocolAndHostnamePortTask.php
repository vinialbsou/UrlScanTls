<?php

namespace App\Tasks\TestSsl;

use Illuminate\Http\Request;

class GenerateUriFromProtocolAndHostnamePortTask
{
    /**
     * "testssl.sh <URI>", where <URI> is:
     *  <URI> host|host:port|URL|URL:port   port 443 is default, URL can only contain HTTPS protocol)
     * @param Request $request
     * @return float|bool|int|string|null
     */
    public static function run(Request $request): float|bool|int|string|null

    {
        $hostname = $request->input('hostname');
        $protocol = $request->input('protocol') ?? 'https';
        $port = $request->input('port') ?? '';

        // Add https:// url for https, other protocols need starttls. Add port if not empty to url/hostname
        if ($protocol === 'https' && !str_contains($hostname, 'https')) {
            return 'https://' . $hostname . ($port ? ':' . $port : '');
        }
        else {
            return $hostname . ($port ? ':' . $port : '');
        }


    }
}
