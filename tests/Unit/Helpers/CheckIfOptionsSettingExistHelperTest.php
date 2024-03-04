<?php

namespace Tests\Unit\Helpers;

use App\Helpers\CheckIfOptionsSettingExistHelper;
use Tests\TestCase;

class CheckIfOptionsSettingExistHelperTest extends TestCase
{
    /** @dataProvider dataTest */
    public function testRun($expected, $input)
    {
        $response = CheckIfOptionsSettingExistHelper::run($input);
        $this->assertEquals($expected, $response);

    }

    /**
     * @return array[]
     */
    public function dataTest(): array
    {
        return [
            '--crime' => [
                false, //that should need to return from method
                '--crime#$' //will be inputted for tests
            ],
            '--robot' => [
                true,
                '--robot'
            ],
            '--breach' => [
                true,
                '--breach'
            ],
            '--poodle' => [
                true,
                '--poodle'
            ],
            '--tls-fallback' => [
                true,
                '--tls-fallback'
            ],
            '--sweet32' => [
                true,
                '--sweet32'
            ],
            'multiple options' => [
                true,
                '--vulnerable,--bugs,--client-simulation'
            ],
            'empty option' => [
                false,
                ''
            ],
            'option does not exists' => [
                false,
                '--tester9'
            ],
            'dangerous command 1' => [
                false
,
                'rm -rf*'],
            'dangerous command 2' => [
                false
                ,
                'mv /home/user/* /dev/null'],

        ];
    }
}
