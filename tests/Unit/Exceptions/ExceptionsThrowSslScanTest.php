<?php

namespace Tests\Unit\Exceptions;

use App\Exceptions\ValidatorException;
use App\Validators\TestSsl\TestSslScanValidator;
use Illuminate\Http\Request;
use Tests\TestCase;

/**
 * @method assertJsonStructure($expected, false|string $json_encode)
 */
class ExceptionsThrowSslScanTest extends TestCase
{

    /** @dataProvider dataTest
     * @throws ValidatorException
     */
    public function testRun($expected, $input)
    {
        $request = Request::create('/api/scan1/createScan', 'POST', ['hostname' => $input['hostname'],
                'optionsSetting' => $input['optionsSetting'],
                'priority' => $input['priority'],
                'ignoreCache' => $input['ignoreCache'],
                'protocol' => $input['protocol'],
                'port' => $input['port']
            ]
        );

        try {
            TestSslScanValidator::run($request);
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
            'return throw of invalid hostname' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "hostname" => [
                                "no dots, aborting"
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'erwer',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443]
            ],
            'return throw of required hostname' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "hostname" => [
                                "The hostname field is required."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => '',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443]
            ],
            'return throw of required priority' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "priority" => [
                                "The priority field is required."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '',
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443]
            ],
            'return throw of priority must be a number' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "priority" => [
                                "The priority must be a number."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => 'asdas',
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443]
            ],
            'return throw of ignore cache must be bool' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "ignoreCache" => [
                                "The ignore cache field must be true or false."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '1000',
                    'ignoreCache' => 'a',
                    'protocol' => 'https',
                    'port' => 443]
            ],
            'return throw of protocol is required' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "protocol" => [
                                "The protocol field is required."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '1000',
                    'ignoreCache' => '1',
                    'protocol' => '',
                    'port' => 443]
            ],
            'return throw of protocol is invalid' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "protocol" => [
                                "The selected protocol is invalid."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '1000',
                    'ignoreCache' => '1',
                    'protocol' => 'asddsa',
                    'port' => 443]
            ],
            'return throw of port is required' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "port" => [
                                "The port field is required."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '1000',
                    'ignoreCache' => '1',
                    'protocol' => 'https',
                    'port' => '']
            ],
            'return throw of port must be a number' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "port" => [
                                "The port must be an integer."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => []],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '1000',
                    'ignoreCache' => '1',
                    'protocol' => 'https',
                    'port' => 'tests@#$']
            ],
            'Success' => [
                ["status" => [
                    "statusCode" => -100,
                    "statusText" => [
                        "text" => [
                            "port" => [
                                "The port must be an integer."
                            ]
                        ],
                        "message:" => "Parser-error"
                    ]
                ],
                    "data" => [
                        "reportCode" => "637d22d2bab71",
                        "text" => "FileScan being generated"
                    ]],
                ['hostname' => 'google.com',
                    'optionsSetting' => '',
                    'priority' => '1000',
                    'ignoreCache' => '1',
                    'protocol' => 'https',
                    'port' => '443']
            ],
        ];
    }
}
