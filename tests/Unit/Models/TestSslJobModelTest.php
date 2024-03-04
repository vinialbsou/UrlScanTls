<?php

namespace Tests\Unit\Models;

use App\Models\TestSslJobModel;
use Database\Seeders\TestSslJobSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use JetBrains\PhpStorm\NoReturn;
use PhpParser\JsonDecoder;
use Tests\TestCase;

class TestSslJobModelTest extends TestCase
{
    //use RefreshDatabase;

    /** @dataProvider dataTest */
    #[NoReturn] public function testRun($table, $input)
    {
        // Run the DatabaseSeeder...
        //$this->seed();

        // Run a specific seeder...
        $this->seed(TestSslJobSeeder::class);
        $response = $input;
        $this->assertDatabaseHas($table, $response);

        //$this->markTestSkipped('It is not recognize the jobs table anymore');
    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        $table = (new TestSslJobModel())->getTable();

        return [
            'using uol.com' => [
                $table,
                [
                    "payload" => '{"uuid":"6c5a36ac-28e0-4929-b665-9b627c85150d","displayName":"App\\Jobs\\RunScanTestSslJobs","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":2,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":1,"retryUntil":null,"data":{"commandName":"App\\Jobs\\RunScanTestSslJobs","command":"O:27:\"App\\Jobs\\RunScanTestSslJobs\":5:{s:10:\"\u0000*\u0000command\";a:12:{i:1;s:12:\".\/testssl.sh\";i:2;s:8:\"--ip=one\";i:3;s:14:\"--warnings=off\";i:4;s:20:\"--connect-timeout=10\";i:5;s:20:\"--openssl-timeout=10\";i:6;s:7:\"--quiet\";i:7;s:6:\"--full\";i:8;s:11:\"--phone-out\";i:9;s:7:\"--hints\";i:10;s:82:\"--htmlfile=\/var\/www\/sslbrain-tlsscan\/storage\/test-ssl-scan-file\/63a0ab627da31.html\";i:11;s:89:\"--jsonfile-pretty=\/var\/www\/sslbrain-tlsscan\/storage\/test-ssl-scan-file\/63a0ab627da31.json\";i:12;s:19:\"https:\/\/uol.com:443\";}s:13:\"\u0000*\u0000reportCode\";s:13:\"63a0ab627da31\";s:11:\"\u0000*\u0000jsonPath\";a:1:{i:0;s:2:\"11\";}s:11:\"\u0000*\u0000htmlPath\";a:1:{i:0;s:2:\"10\";}s:5:\"queue\";s:11:\"tlsscan1000\";}"}}'
                ]
            ],
            'using google.com' => [
                $table,
                [
                    "payload" => '{"uuid":"1449b7a4-01b6-4a1e-a11d-0967fb7c3ee1","displayName":"App\\Jobs\\RunScanTestSslJobs","job":"Illuminate\\Queue\\CallQueuedHandler@call","maxTries":2,"maxExceptions":null,"failOnTimeout":false,"backoff":null,"timeout":1,"retryUntil":null,"data":{"commandName":"App\\Jobs\\RunScanTestSslJobs","command":"O:27:\"App\\Jobs\\RunScanTestSslJobs\":5:{s:10:\"\u0000*\u0000command\";a:12:{i:1;s:12:\".\/testssl.sh\";i:2;s:8:\"--ip=one\";i:3;s:14:\"--warnings=off\";i:4;s:20:\"--connect-timeout=10\";i:5;s:20:\"--openssl-timeout=10\";i:6;s:7:\"--quiet\";i:7;s:6:\"--full\";i:8;s:11:\"--phone-out\";i:9;s:7:\"--hints\";i:10;s:82:\"--htmlfile=\/var\/www\/sslbrain-tlsscan\/storage\/test-ssl-scan-file\/63a0abd0b93c1.html\";i:11;s:89:\"--jsonfile-pretty=\/var\/www\/sslbrain-tlsscan\/storage\/test-ssl-scan-file\/63a0abd0b93c1.json\";i:12;s:25:\"https:\/\/google.com.br:443\";}s:13:\"\u0000*\u0000reportCode\";s:13:\"63a0abd0b93c1\";s:11:\"\u0000*\u0000jsonPath\";a:1:{i:0;s:2:\"11\";}s:11:\"\u0000*\u0000htmlPath\";a:1:{i:0;s:2:\"10\";}s:5:\"queue\";s:11:\"tlsscan1000\";}"}}'
                ]
            ],
        ];
    }
}
