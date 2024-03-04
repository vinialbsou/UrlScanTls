<?php

namespace App\Http\Controllers;

use App\Actions\TestSsl\CreateOrGetExistScanAction;
use App\Actions\TestSsl\GetPropertiesScanAction;
use App\Actions\TestSsl\GetScanFileStatusAction;
use App\Actions\TestSsl\GetScanWithDataAction;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TlsScanController extends Controller
{

    /**
     * @param Request $request
     * @return JsonResponse|mixed|string
     * @throws Exception
     */
    public function createScan(Request $request): mixed
    {
        return CreateOrGetExistScanAction::run($request);
    }


    /**
     * @param Request $request
     * @return bool|array|string
     * @throws Exception
     */
    public function getScanStatusWithResult(Request $request): bool|array|string
    {
        return GetScanWithDataAction::run($request);
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed
     * @throws Exception
     */
    public function getScanStatus(Request $request): mixed
    {
        return GetScanFileStatusAction::run($request);
    }


    /**
     * @return JsonResponse|string
     * @throws Exception
     */
    public function getTestSslProperties(): JsonResponse|string
    {
        return GetPropertiesScanAction::run();
    }
}
