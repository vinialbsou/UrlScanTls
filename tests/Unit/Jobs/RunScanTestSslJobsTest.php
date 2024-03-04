<?php

namespace Tests\Unit\Jobs;

use App\Jobs\RunScanTestSslJobs;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class RunScanTestSslJobsTest extends TestCase
{
    /**
     * A basic feature test example.
     * @dataProvider dataTest
     * @return void
     */
    public function testRun($input)
    {
        Queue::fake([RunScanTestSslJobs::class]);

        $priority = config('tlsscan.queue.prefixName') . '1000';

        RunScanTestSslJobs::dispatch($input, '45670736446')->onQueue($priority);

        // Assert a job was pushed twice...
        Queue::assertPushed(RunScanTestSslJobs::class);

    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        return [
            'using google to queue' => [
                [
                    1 => "./testssl.sh",
                    2 => "--fast",
                    3 => "--ids-friendly",
                    4 => "--ip=one",
                    5 => "--bugs",
                    6 => "--sneaky",
                    7 => "--append",
                    8 => "--bugs",
                    9 => "--phone-out",
                    10 => "--full",
                    11 => "--htmlfile=/var/www/sslbrain-tlsscan/storage/test-ssl-scan-file/3.0.8/6377c81529021.html",
                    12 => "--jsonfile-pretty=/var/www/sslbrain-tlsscan/storage/test-ssl-scan-file/3.0.8/6377c81529021.json",
                    13 => "https://google.com"
                ]
            ],

        ];
    }
}
