<?php

namespace App\Helpers;

use Exception;

class GenerateUniqueReportCodeHelper
{
    /**
     * @param int $length
     * @return string
     */
    public static function run(int $length = 16): string
    {
        // String of all alphanumeric character
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}
