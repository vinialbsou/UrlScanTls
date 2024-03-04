<?php

namespace App\Helpers;

class CheckIfHostExistsByNameHelper
{
    public static function run($hostname, $try_a = true): mixed
    {
        // get AAAA records for $host,
        // if $try_a is true, if AAAA fails, it tries for A
        // results are returned in an array of ips found matching type
        // otherwise returns false

        $dns6 = dns_get_record($hostname, DNS_AAAA);
        if ($try_a) {
            $dns4 = dns_get_record($hostname, DNS_A);
            $dns = array_merge($dns4, $dns6);
        } else {
            $dns = $dns6;
        }
        $ip6 = array();
        $ip4 = array();
        foreach ($dns as $record) {
            if ($record["type"] == "A") {
                $ip4[] = $record["ip"];
            }
            if ($record["type"] == "AAAA") {
                $ip6[] = $record["ipv6"];
            }
        }
        if (count($ip6) < 1) {
            if ($try_a) {
                if (count($ip4) < 1) {
                    return false;
                } else {
                    foreach ($ip4 as $ip){
                        if(!ValidateIPHelper::run($ip)){
                            return false;
                        }
                    }
                    return true;
                }
            } else {
                return false;
            }
        } else {
            foreach ($ip6 as $ip){
                if(!ValidateIPHelper::run($ip, true)){
                    return false;
                }
            }
            return true;
        }
    }
}
