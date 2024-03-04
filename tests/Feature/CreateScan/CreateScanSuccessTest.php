<?php

namespace Tests\Feature\CreateScan;

use Illuminate\Http\Response;
use Tests\TestCase;
use Tests\TestCaseWithFakeQueue;

class CreateScanSuccessTest extends TestCaseWithFakeQueue
{
    private string $createScanRouteV1 = 'tlsscan/v1/createScan';

    /** @dataProvider dataTestShouldRunSuccessfully */
    public function testRouteV1CreateScanShouldRunSuccessfully($expected, $input)
    {
        $response = $this->post($this->createScanRouteV1, $input);

        $this->markTestSkipped('Must be revisited.')->assertEquals($expected['statusCode'], $response->getOriginalContent()['status']['statusCode']);
        $this->markTestSkipped('Must be revisited.')->assertEquals($expected['success']['message'], $response->getOriginalContent()['status']['statusText'][$expected['success']['key']]);
        $response
            ->assertStatus($expected['response'])
            ->assertJsonStructure($expected['jsonStructure']);
    }

    /**
     * @return array[]
     */
    public function dataTestShouldRunSuccessfully(): array
    {

        return [
            // Tests for the hostname field
            'Hostname test - Success - google.com' => [
                [
                    'statusCode' => 0,
                    'success' => [
                        'key' => 'message:',
                        'message' => 'Success'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "message:"
                            ]
                        ],
                        "data" => [
                            "reportCode",
                            "text"
                        ]
                    ]
                ],
                [
                    'hostname' => 'google.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            'Hostname test - Success - go.sd.sd.sd.sds.gle.com' => [
                [
                    'statusCode' => 0,
                    'success' => [
                        'key' => 'message:',
                        'message' => 'Success'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "message:"
                            ]
                        ],
                        "data" => [
                            "reportCode",
                            "text"
                        ]
                    ]
                ],
                [
                    'hostname' => 'go.sd.sd.sd.sds.gle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            'Hostname test - Success - go-gle.com' => [
                [
                    'statusCode' => 0,
                    'success' => [
                        'key' => 'message:',
                        'message' => 'Success'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "message:"
                            ]
                        ],
                        "data" => [
                            "reportCode",
                            "text"
                        ]
                    ]
                ],
                [
                    'hostname' => 'go-gle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            // Tests for the optionsSetting field
            'optionSettings - Success - --bugs,--phone-out,--full' => [
                [
                    'statusCode' => 0,
                    'success' => [
                        'key' => 'message:',
                        'message' => 'Success'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "message:"
                            ]
                        ],
                        "data" => [
                            "reportCode",
                            "text"
                        ]
                    ]
                ],
                [
                    'hostname' => 'gogle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            // Tests for the priority field
            'priority - Success - value: 1' => [
                [
                    'statusCode' => 0,
                    'success' => [
                        'key' => 'message:',
                        'message' => 'Success'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "message:"
                            ]
                        ],
                        "data" => [
                            "reportCode",
                            "text"
                        ]
                    ]
                ],
                [
                    'hostname' => 'gogle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            'priority - Success - value: 1000' => [
                [
                    'statusCode' => 0,
                    'success' => [
                        'key' => 'message:',
                        'message' => 'Success'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "message:"
                            ]
                        ],
                        "data" => [
                            "reportCode",
                            "text"
                        ]
                    ]
                ],
                [
                    'hostname' => 'gogle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
        ];
    }
}
