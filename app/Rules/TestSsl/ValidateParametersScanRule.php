<?php

namespace App\Rules\TestSsl;

use Illuminate\Validation\Rule;

class ValidateParametersScanRule
{
    /**
     * @return array
     */
    public static function run(): array
    {
        return [
            -100 => [
                'hostname' => ['required', 'regex:/^(https?:\/\/)?(?:\/\/|www\.)?[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'port' => 'required|integer|max:65536',
                'protocol' => ['required', Rule::in(array_keys(config('tlsscan.protocols')))],
                'ignoreCache' => ['required', 'boolean'],
                'dns' => ['dns', 'DNSCheckValidation'],
                'priority' => ['required', 'numeric', 'between:1,1000'],
                'scanningType' => ['sometimes', 'string', Rule::in(config('tlsscan.scanningTypes'))],
                'ipAddress' => 'nullable|ip'
            ]
        ];


    }
}
