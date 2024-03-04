<?php

namespace Tests\Unit;

use App\Helpers\RequestValidatorHelper;
use App\Rules\TestSsl\ValidateParametersScanRule;
use Illuminate\Http\Request;
use Tests\TestCase;

class TestsslScanValidatorTest extends TestCase
{
    /** @dataProvider dataTest */
    public function testRun($expected, $input)
    {
        $request = new Request();
        $validator = ValidateParametersScanRule::run();
        $request->merge($input);

        $validationErrors = (new RequestValidatorHelper())->run($request, $validator);

        if($validationErrors['errorCode'] === 0)
        {
            $response = true;
        }else{
            $response = false;
        }
        $this->assertEquals($expected, $response);
    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        return [
            'using google' => [
                true, //that should need to return from method
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1000",
                    "ignoreCache" => "1",
                    "protocol" => "https",
                    "port" => "443"
                ]
            ],
            'validate ignoreCache' => [
                true, //that should need to return from method
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1000",
                    "ignoreCache" => "1", // should be 0 or 1 only
                    "protocol" => "https",
                    "port" => "443"
                ]
            ],
            'validate priority' => [
                true,
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1", //shoud be between 1 and 1000 only
                    "ignoreCache" => "1",
                    "protocol" => "https",
                    "port" => "443"
                ]
            ],
            'validate protocol' => [
                true,
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1000",
                    "ignoreCache" => "1",
                    "protocol" => "https", // validate this
                    "port" => "443"
                ]
            ],
            'validate port' => [
                true,
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1000",
                    "ignoreCache" => "1",
                    "protocol" => "https",
                    "port" => "443"
                ]
            ],
            'validate hostname' => [
                true,
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1000",
                    "ignoreCache" => "1",
                    "protocol" => "https",
                    "port" => "443"
                ]
            ],
            'validate ipAddress' => [
                true,
                [
                    "hostname" => "google.com",
                    "optionsSetting" => "--bugs,--phone-out,--full",
                    "priority" => "1000",
                    "ignoreCache" => "1",
                    "protocol" => "https",
                    "port" => "443",
                    "ipAddress" => "172.188.0.1"
                ]
            ],
        ];
    }
}
