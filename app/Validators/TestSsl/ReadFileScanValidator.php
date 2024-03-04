<?php

namespace App\Validators\TestSsl;

use App\Exceptions\ValidatorException;
use App\Helpers\RequestValidatorHelper;
use App\Helpers\ReturnResultHelper;
use App\Rules\TestSsl\ValidateParameterReadFileScanRule;
use Illuminate\Http\Request;

class ReadFileScanValidator
{
    /**
     * @param Request $request
     * @return void
     * @throws ValidatorException
     */
    public static function run(Request $request): void
    {
        $reportCode = $request->route('reportCode');
        $outputFormat = $request->route('outputFormat');

        $validator = ValidateParameterReadFileScanRule::run($outputFormat);
        $request->merge(['reportCode' => $reportCode, 'outputFormat' => $outputFormat]);

        $validationErrors = (new RequestValidatorHelper())->run($request, $validator);

        if ($validationErrors['errorCode'] < 0) {
            $validatorException = new ValidatorException();
            $validatorException->setJsonResponse((new ReturnResultHelper())->run($validationErrors['errorCode'], [],['text' => $validationErrors['statusText'], 'message:' => config('statusCodeTranslation.parsererror')]));

            throw $validatorException;
        }
    }
}
