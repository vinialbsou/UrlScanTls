<?php

namespace App\Tasks\TestSsl;

use App\Exceptions\ValidatorException;
use App\Validators\TestSsl\ReadFileScanValidator;
use App\Validators\TestSsl\VerifyIfExistFileOnACurrentPathAndGetTheContentValidator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ValidateScanRequestForGetScanTask
{

    /**
     * @param Request $request
     * @return void
     * @throws ValidatorException
     */
    public static function run(Request $request): void
    {
        ReadFileScanValidator::run($request);

    }
}
