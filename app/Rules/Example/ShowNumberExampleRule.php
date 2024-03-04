<?php

namespace App\Rules\Example;

class ShowNumberExampleRule
{
    /**
     * @return array
     */
    public static function run(): array
    {
        return [
            -100 => [
                'number' => 'required|integer|max:4294967295',
                'outputFormat' => 'required|string|in:' . implode(',', config('tlsscan.outputFormatFileSetting')),
                'scanId' => 'required|string|regex:/^[a-zA-Z0-9 ]+$/',
                'hostname' => ['required', 'string', 'regex:/\b(?:\/\/|www\.)?[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],
                'hostname2' => ['required', 'regex:/\b(?:\/\/|www\.)?[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i'],

            ]
        ];
    }
}
