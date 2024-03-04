<?php

namespace App\Rules\TestSsl;


use Illuminate\Validation\Rule;

class ValidatingOutPutFormatParameterRule
{
    public static function run(): array
    {
        return [
            -100 => [
                'outputFormat' => ['required', Rule::in(config('tlsscan.outputFormatSetting'))],
            ]
        ];
    }
}
