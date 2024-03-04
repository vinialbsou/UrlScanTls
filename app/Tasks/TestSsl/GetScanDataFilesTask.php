<?php

namespace App\Tasks\TestSsl;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\get;

class GetScanDataFilesTask
{
    /**
     * @param Request $request
     * @return array
     */
    public static function run(Request $request): array
    {
        $fileName = $request->input('reportCode', 'invalid-report-code');
        $localDisk = Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'));
        $tempScanPath = config('tlsscan.fixedSettings.scanTempDirExtractArchive');

        // check if html and json file exists and add to array
        $scanData = [
            'data' => [
                'html' => null,
                'json' => null
            ]
        ];

        // check if html file exists in the storage disk first
        // then check if the html file exists in the temp directory
        if ($localDisk->exists($fileName . '.html')) {
            $scanData['data']['html'] = $localDisk->get($fileName . '.html');
        } else if (file_exists($tempScanPath . $fileName.'.html')){
            $scanData['data']['html'] = file_get_contents($tempScanPath . $fileName.'.html');
        }

        // check if json file exists in the storage disk first
        // then check if the json file exists in the temp directory
        if ($localDisk->exists($fileName . '.json')) {
            $jsonRaw = $localDisk->get($fileName . '.json');
            $jsonRaw = self::repairPartialJSONFileData($jsonRaw);
            $scanData['data']['json'] = json_decode($jsonRaw, true);
        } else if (file_exists($tempScanPath . $fileName.'.json')){
            $jsonRaw = file_get_contents($tempScanPath . $fileName.'.json');
            $jsonRaw = self::repairPartialJSONFileData($jsonRaw);
            $scanData['data']['json'] = json_decode($jsonRaw, true);
        }

        return $scanData;

    }

    /**
     * @param string $jsonRaw
     * @return string
     */
    private static function repairPartialJSONFileData(string $jsonRaw): string
    {
        // If JSON file contains results, but is not complete, it will be damaged and we will not be able to decode it.
        if (str_contains($jsonRaw, '"scanResult"  : [') && !str_contains($jsonRaw, '"scanTime"')) {
            // We have results, but we are not complete yet.
            // Try to cut off the file at a point where it is complete, so at the end of a section.
            // Gets everything from start to last good ending point.
            $patternMatchGoodPart = '/^(.*"scanResult".*\n\s{20}\]).*$/s';
            // get part of file that matches regular expression
            if (preg_match($patternMatchGoodPart, $jsonRaw, $matches)) {
                // We have a match
                $jsonRaw = $matches[1];
                // And add the missing ending to the file.
                $jsonRaw .= "          }\n          ]\n}\n";
            }

        }
        return $jsonRaw;
    }
}

