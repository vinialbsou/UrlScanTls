<?php

use App\Http\Controllers\TlsScanController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'v1',
    'middleware' => 'cors'
], function () {

    /*
     * url (service://FQDN:port/path)
     *  parametersArray ([—ids-friendly, —fast])
     *  priority 0-1000 (0 highest priority)
     *  ignoreCache      (optional default=false. <null|true:1|false:0>)
     */
    Route::post('createScan', [TlsScanController::class, 'createScan']);

    // GET /api/scan1/getScan/<reportCode>/<html|json|html-json>
    Route::get('getScan/{reportCode}/{outputFormat}', [TlsScanController::class, 'getScanStatusWithResult']);

    Route::get('getScanStatus/{reportCode}', [TlsScanController::class, 'getScanStatus']);

    Route::get('getProperties', [TlsScanController::class, 'getTestSslProperties']);
});
