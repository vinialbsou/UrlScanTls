<?php

namespace App\Helpers;

class CheckIfDnsIsValidHelper
{
    /**
     * @param $string
     * @return bool
     */
    public static function run($string): bool
    {
        // convert param to ascii in case it is idn name i.e. sød.dk
        $StringAsIdn2Safe = idn_to_ascii($string);

        if (preg_match(config('regexp.dns'), $StringAsIdn2Safe))
            return true;
        else
            return false;
    }
}
