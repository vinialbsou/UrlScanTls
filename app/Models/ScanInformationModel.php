<?php

namespace App\Models;

use App\Enumerations\StatusQueue;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 * @property int $id
 * @property int $user_id
 * @property int $client_ip
 * @property string $http_host
 * @property string $http_user_agent
 * @property string $report_code
 * @property string $archive_name
 * @property string $version_testssl
 * @property string $dns_name
 * @property string $priority
 * @property string $scan_hash
 * @property string $status_scan
 * @property string $ignore_cache
 * @property string $protocol
 * @property string $port
 * @property string $options_setting
 * @property string $scanning_type
 * @property Carbon $deleted_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static where(string $string, string $string1, $reportCode)
 * @method static select(string $string)
 * @method static whereIn(string $string, array $reportCodes)
 */
class ScanInformationModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'scan_informations';

    protected $fillable = [
        'user_id',
        'client_ip',
        'http_host',
        'http_user_agent',
        'report_code',
        'version_testssl',
        'dns_name',
        'priority',
        'scan_hash',
        'status_scan',
        'ignore_cache',
        'protocol',
        'port',
        'options_setting',
        'scanning_type'
    ];

    protected $dates = [
        'deleted_at',
        'create_at',
        'update_at'
    ];

    /**
     * @param $reportCode
     * @return void
     * @throws Exception
     */
    public static function remove($reportCode): void
    {
        try {
            ScanInformationModel::where('report_code', '=', $reportCode)->delete();
        } catch (Exception $error) {
            throw new $error;
        }
    }

    /**
     * @throws Exception
     */
    public static function updateScanStatus($reportCode, $statusQueue): void
    {
        try {
            \Log::info("Trying to update the DB with the date and time of the scan.");
            ScanInformationModel::where('report_code', '=', $reportCode)
                ->update([
                    'status_scan' => $statusQueue
                ]);
            // logs the last query
            \Log::info("The last query is: " . ScanInformationModel::query()->toSql());
        } catch (Exception $error) {
            \Log::info("Failed to update the DB with the date and time of the scan.");
            // logs the last query
            \Log::info("The last query is: " . ScanInformationModel::query()->toSql());
            throw new $error;
        }

        \Log::info("Updated the DB with the date and time of the scan.");
    }

    /**
     * @param array $reportCodes
     * @param string $archiveName
     * @return void
     * @throws Exception
     */
    public static function updateArchiveNameByReportCodes(array $reportCodes, string $archiveName): int
    {
        try {
            return ScanInformationModel::whereIn('report_code', $reportCodes)
                ->update([
                    'archive_name' => $archiveName
                ]);
        } catch (Exception $error) {
            throw new $error;
        }
    }

    /**
     * @param $reportCode
     * @return Model|\Illuminate\Database\Query\Builder|null
     */
    public static function getParametersUsed($reportCode): Model|\Illuminate\Database\Query\Builder|null
    {

        return ScanInformationModel::query('dns_name,priority,ignore_cache,protocol,port,options_setting,scanning_type,created_at')
            ->where('report_code', '=', $reportCode)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * @param $scanHash
     * @param StatusQueue $status
     * @return int
     */
    public static function getCountScansByScanHashAndStatus($scanHash, StatusQueue $status = StatusQueue::Pending): int
    {
        return ScanInformationModel::query('id')
            ->where('scan_hash', '=', $scanHash)
            ->where('status_scan', '=', $status)
            ->count();
    }

    /**
     * @param $scanHash
     * @param StatusQueue $status
     * @return Model|\Illuminate\Database\Query\Builder|null
     */
    public static function getLastUnarchivedScanByScanHashAndStatus($scanHash, StatusQueue $status = StatusQueue::Done): Model|\Illuminate\Database\Query\Builder|null
    {
        return ScanInformationModel::query('report_code')
            ->where('scan_hash', '=', $scanHash)
            ->where('status_scan', '=', $status)
            ->whereNull('archive_name')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * @param $scanHash
     * @param StatusQueue $status
     * @return Model|\Illuminate\Database\Query\Builder|null
     */
    public static function getLastArchivedScanByScanHashAndStatus($scanHash, StatusQueue $status = StatusQueue::Done): Model|\Illuminate\Database\Query\Builder|null
    {
        return ScanInformationModel::query('report_code')
            ->where('scan_hash', '=', $scanHash)
            ->where('status_scan', '=', $status)
            ->whereNotNull('archive_name')
            ->orderBy('created_at', 'desc')
            ->first();
    }
}
