<?php

namespace App\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RequestValidatorHelper
{
    /**
     * @param Request $request
     * @param $validators
     * @return array
     */
    public function run(Request $request, $validators): array
    {
        // Error codes worst to best
        // -10000 -1000 -100 -10 0 10 100 1000 10000

        // Default result
        $errorCode = 0;
        $errorsArray = [];

        // Go through each error and build merged array with the lowest status_code found
        foreach ($validators as $validatorErrorCode => $validatorTemplate) {

            $validator = Validator::make($request->all(), $validatorTemplate);

            if ($validator->fails()) {
                if ($validatorErrorCode < $errorCode || $errorCode == 0) {
                    $errorCode = $validatorErrorCode;
                }
                $errorsArray = array_merge($errorsArray, (array)json_decode(json_encode($validator->errors())));
            }
        }
        // return array with errorCode that we can check on and array of errors in statusText
        return [
            'errorCode' => $errorCode,
            'statusText' => $errorsArray
        ];
    }
}
