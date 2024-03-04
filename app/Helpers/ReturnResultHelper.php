<?php

namespace App\Helpers;

use Illuminate\Http\JsonResponse;

class ReturnResultHelper
{

    /**
     * @param int $statusCode
     * @param array $data
     * @param array $statusText
     * @return JsonResponse
     */
    public static function run(int $statusCode = 0, array $data = [], array $statusText = []): JsonResponse
    {

        $response = [
            'status' => [
                'statusCode' => $statusCode,
                'statusText' => $statusText,
            ],
            'data' => $data
        ];

        // Who would not like to know if doing 10 sql calls is slower than doing one with 15 joins...
        // Well now we can find out, if we are not in a known production state
        // If this is not production, add execution time to status
        if (!config('app.debug', true)) {
            global $startMicroTime;
            if (!empty($startMicroTime))
                $response['status']['executionTime'] = microtime(true) - $startMicroTime;
        }

        // Build uniform result, that does not change, every time a developer gets a bright idea
        return response()->json($response);
    }
}
