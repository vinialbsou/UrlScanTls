<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ValidatorException extends Exception
{
    private JsonResponse $jsonResponse;

    /**
     * @param JsonResponse $jsonResponse
     */
    public function setJsonResponse(JsonResponse $jsonResponse)
    {
        $this->jsonResponse = $jsonResponse;
    }

    public function getJsonResponse(): JsonResponse
    {
        return $this->jsonResponse;
    }
}
