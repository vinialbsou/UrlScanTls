<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ValidatorException;
use App\Validators\TestSsl\ReadFileScanValidator;
use App\Validators\TestSsl\TestSslScanValidator;
use Illuminate\Http\Request;
use JetBrains\PhpStorm\NoReturn;
use Tests\TestCase;

/**
 * @method assertJsonStructure($expected, false|string $json_encode)
 */
class ExceptionsThrowReadFileScanValidatorTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     * @dataProvider dataTest
     */
    #[NoReturn] public function testRun($expected, $input): void
    {
        $request = Request::create('/api/scan1/createScan', 'POST', [
                'scanId' => $input['scanId'],
                'outputFormat' => $input['outputFormat']
            ]
        );

        $this->expectException(ValidatorException::class);

        (new ReadFileScanValidator())->run($request);
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
                    'scanId' => '637e1e3683630',
                    'outputFormat' => 'html'
                ]
            ],
        ];
    }
}
