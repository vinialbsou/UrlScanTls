<?php

namespace Tests\Unit\Actions;

use App\Exceptions\ValidatorException;
use App\Helpers\RequestValidatorHelper;
use App\Helpers\ReturnResultHelper;
use App\Rules\TestSsl\ValidateParameterReadFileScanRule;
use App\Tasks\TestSsl\ValidateScanRequestForGetScanTask;
use Exception;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @method assertJsonStructure($expected, false|string $json_encode)
 */
class ReadFileScanSslActionTest extends TestCase
{

    /** @dataProvider dataTest
     * @throws ValidatorException|Exception
     */
    public function testRun($expected, $input)
    {$request = Request::create('/tlsscan/v1/getScan/{reportCode}/{outputFormat}', 'GET', [
              'reportCode' => $input['reportCode'],
              'outputFormat' => $input['outputFormat']
          ]
      );


        try {
            $validator = ValidateParameterReadFileScanRule::run($input['outputFormat']);

            $validationErrors = (new RequestValidatorHelper())->run($request, $validator);

            if ($validationErrors['errorCode'] < 0) {
                $validatorException = new ValidatorException();
                $validatorException->setJsonResponse((new ReturnResultHelper())->run($validationErrors['errorCode'], [],['text' => $validationErrors['statusText'], 'message:' => config('statusCodeTranslation.parsererror')]));

                throw $validatorException;
            }
            ValidateScanRequestForGetScanTask::run($request);
            $this->assertEquals(json_encode($expected), json_encode($expected));
            $this->assertEquals(json_encode($expected), json_encode($expected));

        } catch (ValidatorException $error) {
            $this->assertJson(json_encode($error->getJsonResponse()->getData()));
            $this->assertEquals(json_encode($error->getJsonResponse()->getData()), json_encode($expected));
        }
    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {

        return [
            'return throw of required scanId' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "reportCode" => [
                                "The report code format is invalid."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                [
                    'reportCode' => '637f5a@',
                    'outputFormat' => 'html',
                ]
            ],
            'return throw of invalid outputFormat' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "outputFormat" => [
                                "The selected output format is invalid."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                [
                    'reportCode' => '637f5adc9331e',
                    'outputFormat' => '637f5a@',
                ]
            ],
        ];
    }
}
