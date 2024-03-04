<?php

namespace Tests\Unit\Tasks;

use App\Exceptions\ValidatorException;
use App\Tasks\TestSsl\ValidateScanRequestForGetScanTask;
use App\Validators\TestSsl\ReadFileScanValidator;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

class CheckExceptionOnReadFileScanTaskTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @dataProvider dataTest
     */
    #[NoReturn] public function testRun($expected, $input): void
    {
        $request = Request::create('/tlsscan/v1/createScan', 'POST', [
                'reportCode' => $input['reportCode'],
                'outputFormat' => $input['outputFormat']
            ]
        );

        $this->expectException(ValidatorException::class);

        (new ValidateScanRequestForGetScanTask())->run($request);
    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        return [
            'return throw of invalid scanId' => [
                false,
                [
                    'reportCode' => '637e1e3683630',
                    'outputFormat' => 'html'
                ]
            ],
            'return throw of invalid both parameters' => [
                false,
                [
                    'reportCode' => '',
                    'outputFormat' => ''
                ]
            ],
        ];
    }
}
