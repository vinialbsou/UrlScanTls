<?php

namespace App\Tasks\TestSsl;

use App\Exceptions\ValidatorException;
use App\Validators\TestSsl\TestSslScanValidator;
use Illuminate\Http\Request;

class ValidateScanRequestTask
{
    /**
     * @throws ValidatorException
     */
    public static function run(Request $request): void
    {
        TestSslScanValidator::run($request);
    }
}
