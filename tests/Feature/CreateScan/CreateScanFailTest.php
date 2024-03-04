<?php

namespace Tests\Feature\CreateScan;

use Illuminate\Http\Response;
use Tests\TestCaseWithFakeQueue;

class CreateScanFailTest extends TestCaseWithFakeQueue
{
    private string $createScanRouteV1 = 'tlsscan/v1/createScan';

    /** @dataProvider dataTestShouldThrowException */
    public function testRouteV1CreateScanShouldThrowException($expected, $input)
    {
        $response = $this->post($this->createScanRouteV1, $input);

        $this->markTestSkipped('Must be revisited.')->assertEquals($expected['statusCode'], $response->getOriginalContent()['status']['statusCode']);
        $this->markTestSkipped('Must be revisited.')->assertEquals($expected['error']['message'], $response->getOriginalContent()['status']['statusText']['text'][$expected['error']['key']][0]);
        $response
            ->assertStatus($expected['response'])
            ->assertJsonStructure($expected['jsonStructure']);
    }

    /**
     * @return array[]
     */
    public function dataTestShouldThrowException(): array
    {

        return [
            // Tests for the hostname field
            'The hostname field is required - empty field' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'hostname',
                        'message' => 'The hostname field is required.'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "hostname"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => '',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            'no dots, aborting - google' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'hostname',
                        'message' => 'no dots, aborting'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "hostname"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'google',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            'TLD not found - goog.m' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'hostname',
                        'message' => 'TLD not found'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "hostname"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'goog.m',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            'TLD not found - asd.ssa' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'hostname',
                        'message' => 'TLD not found'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "hostname"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'asd.ssa',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            'This DNS is not valid - go@gle.com' => [
                [
                    'statusCode' => -102,
                    'error' => [
                        'key' => 'dns',
                        'message' => 'This DNS is not valid'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "dns"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'go@gle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            'This DNS is not valid - go#gle.com' => [
                [
                    'statusCode' => -102,
                    'error' => [
                        'key' => 'dns',
                        'message' => 'This DNS is not valid'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "dns"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'go#gle.com',
                    'optionsSetting' => '--bugs,--phone-out,--full',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            // Tests for the optionsSetting field
            'One of the options is not permitted - --thisSettingDoesntExist' => [
                [
                    'statusCode' => -108,
                    'error' => [
                        'key' => 'optionSettings',
                        'message' => 'One of the options is not permitted'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "optionSettings"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'go#gle.com',
                    'optionsSetting' => '--thisSettingDoesntExist',
                    'priority' => 1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

            // Tests for the priority field
            'The priority must be between 1 and 1000 - negative value' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'priority',
                        'message' => 'The priority must be between 1 and 1000.'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "priority"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'gogle.com',
                    'optionsSetting' => '--full,--phone-out,--hints',
                    'priority' => -1000,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            'The priority must be between 1 and 1000 - zero' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'priority',
                        'message' => 'The priority must be between 1 and 1000.'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "priority"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'gogle.com',
                    'optionsSetting' => '--full,--phone-out,--hints',
                    'priority' => 0,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],
            'The priority must be between 1 and 1000 - 1001' => [
                [
                    'statusCode' => -100,
                    'error' => [
                        'key' => 'priority',
                        'message' => 'The priority must be between 1 and 1000.'
                    ],
                    'response' => Response::HTTP_OK,
                    'jsonStructure' => [
                        "status" => [
                            "statusCode",
                            "statusText" => [
                                "text" => [
                                    "priority"
                                ],
                                "message:"
                            ]
                        ],
                        "data" => []
                    ]
                ],
                [
                    'hostname' => 'gogle.com',
                    'optionsSetting' => '--full,--phone-out,--hints',
                    'priority' => 1001,
                    'ignoreCache' => 1,
                    'protocol' => 'https',
                    'port' => 443
                ]
            ],

        ];
    }

}
