<?php

namespace Tests\Unit\Tasks\Helpers;

use App\Helpers\GetTLDv3Helper;
use Tests\TestCase;

class GetTLDv3HelperTest extends TestCase
{
    /** @dataProvider dataTest */
    public function testRun($expected, $input)
    {
        $response = GetTLDv3Helper::run($input);

        $this->assertEquals($expected, $response);
    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        return [
            "notyours.com sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "notyours.com",
                    "sub_domain" => "notyours.com",
                    "tld" => "com",
                    "is_icann" => "icann"
                ],
                'notyours.com'
            ],
            "www.xn--c1yn36f.gl - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "xn--c1yn36f.gl",
                    "sub_domain" => "xn--c1yn36f.gl",
                    "tld" => "gl",
                    "is_icann" => "icann"
                ],
                'www.xn--c1yn36f.gl'
            ],
            "rfc.1123.net - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "1123.net",
                    "sub_domain" => "1123.net",
                    "tld" => "net",
                    "is_icann" => "icann"
                ],
                'rfc.1123.net'
            ],
            "i6.pt - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "i6.pt",
                    "sub_domain" => "i6.pt",
                    "tld" => "pt",
                    "is_icann" => "icann"
                ],
                'i6.pt'
            ],
            "test.fqdn12.shaw - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "fqdn12.shaw",
                    "sub_domain" => "fqdn12.shaw",
                    "tld" => "shaw",
                    "is_icann" => "icann"
                ],
                'test.fqdn12.shaw'
            ],
            "multi-dash.level.notyours.se - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "notyours.se",
                    "sub_domain" => "level.notyours.se",
                    "tld" => "se",
                    "is_icann" => "icann"
                ],
                'multi-dash.level.notyours.se'
            ],
            "www.notyours.sandvikcoromant - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "notyours.sandvikcoromant",
                    "sub_domain" => "notyours.sandvikcoromant",
                    "tld" => "sandvikcoromant",
                    "is_icann" => "icann"
                ],
                'www.notyours.sandvikcoromant'
            ],
            "abc.notyours.dk - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "notyours.dk",
                    "sub_domain" => "notyours.dk",
                    "tld" => "dk",
                    "is_icann" => "icann"
                ],
                'abc.notyours.dk'
            ],
            "notyours.dk - sucess" => [
                [
                    "success" => 1,
                    "error" => "",
                    "purchased_domain" => "notyours.dk",
                    "sub_domain" => "notyours.dk",
                    "tld" => "dk",
                    "is_icann" => "icann"
                ],
                'notyours.dk'
            ],
            "test - no dots, aborting" => [
                [
                    'success' => -1,
                    'error' => 'no dots, aborting',
                    'purchased_domain' => 'test',
                    'sub_domain' => 'test',
                    'tld' => 'test',
                    'is_icann' => 'private'
                ],
                'test'
            ],
            "notyours.ddd - TLD not found" => [
                [
                    "success" => 1,
                    "error" => "TLD not found",
                    "purchased_domain" => "notyours.ddd",
                    "sub_domain" => "notyours.ddd",
                    "tld" => "ddd",
                    "is_icann" => "private"
                ],
                'notyours.ddd'
            ],

        ];
    }
}
