<?php

namespace App\Validators\TestSsl;

use App\Exceptions\ValidatorException;
use App\Helpers\CheckIfDnsIsValidHelper;
use App\Helpers\CheckIfHostExistsByNameHelper;
use App\Helpers\CheckIfOptionsSettingExistHelper;
use App\Helpers\GetTLDv3Helper;
use App\Helpers\RequestValidatorHelper;
use App\Helpers\ReturnResultHelper;
use App\Helpers\ValidateIPHelper;
use App\Rules\TestSsl\ValidateParametersScanRule;
use Illuminate\Http\Request;


class TestSslScanValidator
{
    /**
     * Validate All Parameters from input
     *
     * @param Request $request
     * @return void
     * @throws ValidatorException
     */
    public static function run(Request $request): void
    {
        $hostname = $request->input('hostname');
        // Remove everything before the body tag

        $optionsSetting = $request->input('optionsSetting');

        $validator = ValidateParametersScanRule::run();
        $validatorException = new ValidatorException();

        $validationErrors = (new RequestValidatorHelper())->run($request, $validator);

        if ($validationErrors['errorCode'] < 0) {
            $validatorException = new ValidatorException();
            $validatorException->setJsonResponse((new ReturnResultHelper())->run($validationErrors['errorCode'], [],['text' => $validationErrors['statusText'], 'message:' => config('statusCodeTranslation.parsererror')]));

            throw $validatorException;
        }

        if(!ValidateIPHelper::run($request->input('ipAddress')) && !empty($request->input('ipAddress'))){
            $validatorException->setJsonResponse((new ReturnResultHelper())->run(-110, [],['text' => ['optionSettings' => [config('statusCodeTranslation.-110')]], 'message:' => config('statusCodeTranslation.parsererror')]));

            throw $validatorException;
        }

        $getTld = GetTLDv3Helper::run($hostname);
        if (!empty($getTld["error"])) {
            $validatorException->setJsonResponse((new ReturnResultHelper())->run(-100, [],['text' => ['hostname' => [$getTld["error"]]], 'message:' => config('statusCodeTranslation.parsererror')]));

            throw $validatorException;
        }

        if(!empty($optionsSetting)){
            if (!CheckIfOptionsSettingExistHelper::run($optionsSetting)) {
                $validatorException->setJsonResponse((new ReturnResultHelper())->run(-108, [],['text' => ['optionSettings' => [config('statusCodeTranslation.-108')]], 'message:' => config('statusCodeTranslation.parsererror')]));

                throw $validatorException;
            }
        }

        if (!CheckIfDnsIsValidHelper::run($hostname)) {
            $validatorException->setJsonResponse((new ReturnResultHelper())->run(-102, [],['text' => ['dns' => [config('statusCodeTranslation.-102')]], 'message:' => config('statusCodeTranslation.parsererror')]));

            throw $validatorException;
        }

        if (!CheckIfHostExistsByNameHelper::run($hostname)) {
            $validatorException->setJsonResponse((new ReturnResultHelper())->run(-102, [],['text' => ['dns' => [config('statusCodeTranslation.-102')]], 'message:' => config('statusCodeTranslation.parsererror')]));

            throw $validatorException;
        }

    }
}
