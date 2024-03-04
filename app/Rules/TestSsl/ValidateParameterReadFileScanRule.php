<?php

namespace App\Rules\TestSsl;

class ValidateParameterReadFileScanRule
{
    /**
     * @param null $outputFormat
     * @return array
     */
    public static function run($outputFormat = null): array
    {
        if ($outputFormat) {
            return [
                -100 => [
                    'reportCode' => 'required|string|regex:/^[a-zA-Z0-9 ]+$/',
                    'outputFormat' => ['required','string','in:' . implode(',', config('tlsscan.outputFormatFileSetting'))],
                ]
            ];
        } else {
            return [
                -100 => [
                    'reportCode' => 'required|string|regex:/^[a-zA-Z0-9 ]+$/',
                ]
            ];
        }

    }
}
