<?php

namespace App\Tasks\TestSsl\Filesystem;

use App\Helpers\ReturnResultHelper;
use App\Models\ScanInformationModel;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use League\Flysystem\FilesystemException;
use Storage;
use ZipArchive;

class GetScanFromArchiveTask
{
    /**
     * @throws Exception
     */
    public static function run(Model $archivedScan): ?JsonResponse
    {
        // verify if $archivedScan is an instance of ScanInformationModel
        if (!($archivedScan instanceof ScanInformationModel)) {
            // throw new Exception('The $archivedScan is not an instance of ScanInformationModel');
            // Todo it's probably better to throw an exception here
            return null;
        }


        $archiveDisk = Storage::disk(config('tlsscan.fixedSettings.scanArchiveDisk', 's3'));
        $storageDisk = Storage::disk(config('tlsscan.fixedSettings.storageDisk', 'scanssl'));

        $tempScanPath = VerifyAndCreateTempDirectoryToExtractScanFilesFromArchiveTask::run();
//        dd($tempScanPath);

        if(!file_exists($tempScanPath . $archivedScan->report_code.'.html') && !file_exists(config('tlsscan.fixedSettings.pathFileScanSave') . $archivedScan->report_code.'.html')) {
            if (MoveArchiveFileFromS3ToTempDirectoryTask::run($archivedScan->archive_name, $tempScanPath)) {

                $scanArchivePath = $tempScanPath . $archivedScan->archive_name;

                $zip = new ZipArchive();
                $zip->open($scanArchivePath);
                if($zip->extractTo($tempScanPath)) {
                    $zip->close();
                } else {
                    //throw new Exception('The archive file could not be extracted');
                    // Todo it's probably better to throw an exception here
                    return null;
                }

            } else {
                //throw new Exception('The archive file does not exist in the S3 bucket');
                // Todo it's probably better to throw an exception here
                return null;
            }
        }

        return (new ReturnResultHelper())->run(0, ['reportCode' => $archivedScan->report_code, 'text' => config('statusCodeTranslation.11')], ['message:' => config('statusCodeTranslation.success')]);

    }
}
