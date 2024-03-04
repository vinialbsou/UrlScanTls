<?php

namespace App\Helpers;

class ValidateIPHelper
{
    public static function run($ipAddress, $ipv6 = false): bool
    {
        if(!$ipv6){
            return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE);
        } else {
            return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        }
    }
}
