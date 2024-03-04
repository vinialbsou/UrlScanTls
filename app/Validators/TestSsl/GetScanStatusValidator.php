<?php

namespace App\Validators\TestSsl;

use App\Exceptions\ValidatorException;
use App\Helpers\RequestValidatorHelper;
use App\Helpers\ReturnResultHelper;
use App\Rules\TestSsl\ValidateParameterReadFileScanRule;
use Exception;
use Illuminate\Http\Request;

class GetScanStatusValidator
{
    /**
     * @param Request $request
     * @return void
     * @throws Exception
     */
    public static function run(Request $request): void
    {
        $validator = ValidateParameterReadFileScanRule::run();
        $request->merge(['reportCode' => $request->route('reportCode')]);

        $validationErrors = (new RequestValidatorHelper())->run($request, $validator);

        if ($validationErrors['errorCode'] < 0) {
            $validatorException = new ValidatorException();
            $validatorException->setJsonResponse((new ReturnResultHelper())->run($validationErrors['errorCode'], [],['text' => $validationErrors['statusText'], 'message:' => config('statusCodeTranslation.parsererror')]));
            if ($validatorException->getCode() === 0) {
                $validatorException->setJsonResponse((new ReturnResultHelper())->run(-106, [],['text' => ['error' => config('statusCodeTranslation.-106')], 'message:' => config('statusCodeTranslation.parsererror')]));
            } else {
                $validatorException->setJsonResponse((new ReturnResultHelper())->run(-107, [],['text' => ['error' => config('statusCodeTranslation.-107')], 'message:' => config('statusCodeTranslation.parsererror')]));
            }
            throw $validatorException;
        }
    }
}
