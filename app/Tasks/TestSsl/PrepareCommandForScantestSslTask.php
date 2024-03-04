<?php

namespace App\Tasks\TestSsl;

use App\Helpers\GenerateUniqueReportCodeHelper;
use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;

class PrepareCommandForScantestSslTask
{
    /**
     * Preparing the commando for ScriptShell Testssl
     * @param Request $request
     * @param $fileScanPath
     * @return array|Repository|string|Application
     * @throws Exception
     */
    public static function run(Request $request, $fileScanPath): array|Repository|string|Application
    {
        $scanId = GenerateUniqueReportCodeHelper::run(config('tlsscan.fixedSettings.reportCodeUniqueIdLength', 16));
        $protocol = $request->input('protocol') ?? 'https';
        $htmlFileName = $fileScanPath . $scanId . config('tlsscan.fixedSettings.extensionHtmlFile');
        $jsonFileName = $fileScanPath . $scanId . config('tlsscan.fixedSettings.extensionJsonFile');

        $url = GenerateUriFromProtocolAndHostnamePortTask::run($request);

        $optionsSetting = explode(',', $request->input('optionsSetting'));
        if ($protocol !== 'https') // https we just add as url, other protocols need starttls
        {
            $optionsSetting[] = '--starttls=' . $protocol;
        }
        if(!empty($request->input('ipAddress'))){
            $optionsSetting[] = '--ip=' . $request->input('ipAddress');
        }

        $fileToExecute = config('tlsscan.fixedSettings.scanAppRun', './testssl.sh');

        $command = "$fileToExecute";
        $defaultStatic = config('tlsscan.testsslParametersFixedEnabled');

        if ((empty($optionsSetting[0]))) {
            $processArray = array_merge([$scanId], [$command], $defaultStatic, ['--htmlfile=' . $htmlFileName], ['--jsonfile-pretty=' . $jsonFileName], [$url]);
        } else {
            $processArray = array_merge([$scanId], [$command], $defaultStatic, $optionsSetting, ['--htmlfile=' . $htmlFileName], ['--jsonfile-pretty=' . $jsonFileName], [$url]);
        }

        return $processArray;

    }
}
