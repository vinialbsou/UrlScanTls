<?php

namespace App\Jobs;

use App\Enumerations\StatusQueue;
use App\Models\ScanInformationModel;
use App\Tasks\TestSsl\Filesystem\DeleteDataFilesIfExistsByReportCodeTask;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Redis\LimiterTimeoutException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;

class RunScanTestSslJobs implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

//    public int $tries = 2; // how many times to try to process this job
//    public int $timeout = 900; // The number of seconds the process is allowed to run before timing out and failing

    protected $command;
    protected $reportCode;
    protected $jsonPath;
    protected $htmlPath;

    /**
     * Create a new job instance.
     * Dispatching a job with a command built
     *
     * @return void
     */
    public function __construct($command, $reportCode)
    {
        $this->command = $command;
        $this->reportCode = $reportCode;
        $this->htmlPath = str_replace('--htmlfile=', '', [count($command) - 2]);
        $this->jsonPath = str_replace('--jsonfile-pretty=', '', [count($command) - 1]);

    }

    /**
     * @return void
     * @throws LimiterTimeoutException
     * @throws \Exception
     */
    public function handle(): void
    {
        DeleteDataFilesIfExistsByReportCodeTask::run($this->reportCode);

        $process = new Process (
            $this->command,
            config('tlsscan.fixedSettings.pathTestSsl') . config('tlsscan.fixedSettings.activeVersion'),
            null,
            null,
            config('tlsscan.fixedSettings.scanCommandTimeoutSeconds', 600) // timeout in seconds
        );
        // run is synchronous and start is asynchronous. We need to wait for the result to check if successful.
        // that is why we are using queues, to run multiple processes. We could even get output live here, instead of reading files.
        Log::info('Starting scan: ' . $this->reportCode . ' with command: ' . implode(' ', $this->command));
        $process->run();

        Log::debug('scan complete before wait');

        $process->wait(); // if process is asynchronous, we need to wait for it to finish.

        Log::debug('scan complete after wait');

        // testssl.sh may give exit codes between 1 and 200, when scan is successful but issues were found in the scan.
        // 242-255 issues in scan, connection, bash, etc.
        // https://testssl.sh/doc/testssl.1.html#EXIT-STATUS

        // date used to update the status of the scan in the database
        $date = new Carbon();

        if ($process->getExitCode() < 0 || $process->getExitCode() > 200) {
            Log::error('Scan failed: ' . $this->reportCode . ' exitCode: ' . $process->getExitCode() . ' exitCodeText: ' . $process->getExitCodeText() . ' errorOutput: ' . $process->getErrorOutput());

            // Debug why we are failing
            var_dump(
                $process->isSuccessful(),
                $process->getExitCode(),
                $process->getExitCodeText(),
                $process->getErrorOutput(),
                $process->getWorkingDirectory(),
                $process->getCommandLine(),
                $this->reportCode
            );

            // Todo re-enable this once we know how to handle errors. It will fail the job so it retries.
            // We want to retry only if the error is re-triable, meaning temporary network issue, etc. but not if the dns name does not exist, etc
            // make sure we delete the files, if we are going to retry the scan. not if we are done retrying.
//            $this->fail($process->getErrorOutput());

            // Release the job back into the queue if the exitCode is in the array
            if (in_array($process->getExitCode(), [246, 245, 244, 242, 248, 252, 253, 254], false)) {
                Log::info('Releasing job back into the queue: ' . $this->reportCode . ' exitCode: ' . $process->getExitCode() . ' exitCodeText: ' . $process->getExitCodeText() . ' errorOutput: ' . $process->getErrorOutput());
                $this->release(2);
            } else {
                $this->fail($process->getErrorOutput());

                ScanInformationModel::updateScanStatus($this->reportCode,StatusQueue::Error);
            }

        } else {
            Log::info('Scan completed successfully: ' . $this->reportCode . ' exitCode: ' . $process->getExitCode() . ' exitCodeText: ' . $process->getExitCodeText() . ' errorOutput: ' . $process->getErrorOutput());

            ScanInformationModel::updateScanStatus($this->reportCode,StatusQueue::Done);
        }
    }

    /**
     * @throws Exception
     */
    public function failed($error): void
    {
        Log::error('Job RunScanTestSslJobs failed with: ' . $error);

        ScanInformationModel::updateScanStatus($this->reportCode,StatusQueue::Error);
    }
}
