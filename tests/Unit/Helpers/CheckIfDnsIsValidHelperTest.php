<?php

namespace Tests\Unit\Tasks\Helpers;


use App\Helpers\CheckIfDnsIsValidHelper;
use Tests\TestCase;

class CheckIfDnsIsValidHelperTest extends TestCase
{

    /** @dataProvider dataTest */
    public function testRun($expected, $input)
    {
        $response = CheckIfDnsIsValidHelper::run($input);

        $this->assertEquals($expected, $response);
    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        return [
            "notyours.com sucess" => [
                true,
                'notyours.com'
            ],
            "www.xn--c1yn36f.gl - sucess" => [
                true,
                'www.xn--c1yn36f.gl'
            ],
            "rfc.1123.net - sucess" => [
                true,
                'rfc.1123.net'
            ],
            "i6.pt - sucess" => [
                true,
                'i6.pt'
            ],
            "test.fqdn12.shaw - sucess" => [
                true,
                'test.fqdn12.shaw'
            ],
            "multi-dash.level.notyours.se - sucess" => [
                true,
                'multi-dash.level.notyours.se'
            ],
            "www.notyours.sandvikcoromant - sucess" => [
                true,
                'www.notyours.sandvikcoromant'
            ],
            "abc.notyours.dk - sucess" => [
                true,
                'abc.notyours.dk'
            ],
            "notyours.dk - sucess" => [
                true,
                'notyours.dk'
            ],
            "notyours.ddd - sucess" => [
                true,
                'notyours.ddd'
            ],
            "123.com - true" => [
                true,
                '123.com'
            ],
            "grøn-gas.dk - true" => [
                true,
                'grøn-gas.dk'
            ],
            "test - false" => [
                false,
                'test'
            ],
            "test!.com - false" => [
                false,
                'test!.com'
            ],
            "test|test.com - false" => [
                false,
                'test|test.com'
            ],



        ];
    }
}
