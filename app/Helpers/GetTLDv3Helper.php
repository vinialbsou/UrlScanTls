<?php

namespace App\Helpers;

class GetTLDv3Helper
{
    /**
     * Validate fqdn input
     * @param $fqdn
     * @return array
     */
    public static function run($fqdn): array
    {

        global $public_suffix_list;
        if (!isset($public_suffix_list)) {
            $public_suffix_list = config('publicsuffixlist');
        }

        // vars
        $error_message = '';
        $test_name = '';
        $j = 0;
        $test_fqdn = array();

        // if special chars in fqdn, IDN encode it
        if (preg_match('/[^A-Za-z0-9\.\-\_]/', $fqdn)) $fqdn = idn_to_ascii($fqdn, 0, INTL_IDNA_VARIANT_UTS46);

        // uniforms searches to lower char
        $fqdn = strtolower($fqdn);

        // if leading . remove it
        if (strpos($fqdn, '.') === 0) $fqdn = substr($fqdn, 1);

        // split the fqdn into its parts
        $fqdn_array = explode(".", $fqdn);
        $fqdn_count = count($fqdn_array);

        // if no dots, abort
        if ($fqdn_count < 2) return array(
            'success' => -1,
            'error' => 'no dots, aborting',
            'purchased_domain' => $fqdn,
            'sub_domain' => $fqdn,
            'tld' => $fqdn,
            'is_icann' => 'private'
        );

        // create all possible DNS versions of fqdn, starting with the smallest part.
        for ($i = $fqdn_count - 1; $i >= 0; $i--) {
            $j++;
            // build the parts
            $test_name = $fqdn_array[$i] . "." . $test_name;
            $test_fqdn[$j] = substr($test_name, 0, strlen($test_name) - 1);

        }

        // Find the TLD match in array
        // 0 non-icann default, 1 icann default, 2 non-icann wildcard, 3 icann wildcard, 4 non-icann exception, 5 icann exception
        $max_domain_parts = 5;
        if ($fqdn_count < $max_domain_parts) $max_domain_parts = $fqdn_count;
        for ($i = $max_domain_parts; $i > 0; $i--) {
            if (isset($public_suffix_list[$test_fqdn[$i]])) {
                // matches array

                if ($public_suffix_list[$test_fqdn[$i]] & 1) $is_icann = 'icann';
                else $is_icann = 'non-icann';
                if ($public_suffix_list[$test_fqdn[$i]] & 2) {
                    // is wildcard - return longer name
                    $domain_tld_int = $i + 1;
                } elseif ($public_suffix_list[$test_fqdn[$i]] & 4) {
                    // is exception - return one smaller
                    $domain_tld_int = $i - 1;
                } else {
                    // is default - return +1
                    $domain_tld_int = $i;
                }
                $domain_sub_int = $fqdn_count - 1;
                $domain_purchased_int = $domain_tld_int + 1;

                // detect fqdn's that are impossibly small
                if ($domain_purchased_int > $fqdn_count) $domain_purchased_int = $fqdn_count;
                // if sub is smaller than purchased domain, is purchased domain
                if ($domain_sub_int < $domain_purchased_int) $domain_sub_int = $domain_purchased_int;

                return array(
                    'success' => 1,
                    'error' => $error_message,
                    'purchased_domain' => $test_fqdn[$domain_purchased_int],
                    'sub_domain' => $test_fqdn[$domain_sub_int],
                    'tld' => $test_fqdn[$domain_tld_int],
                    'is_icann' => $is_icann
                );
            }
        }

        // if here - array search failed
        // Did not find anything, DEFAULT * rule applies.
        // Assume tld is single name without dots ie. com or dk
        $error_message = "TLD not found";
        $is_icann = 'private'; // not found in public suffix list so internal
        $domain_purchased_int = 2;
        $domain_tld_int = 1;
        $domain_sub_int = $fqdn_count - 1;
        if ($domain_sub_int < $domain_purchased_int) $domain_sub_int = $domain_purchased_int;
        return array(
            'success' => 1,
            'error' => $error_message,
            'purchased_domain' => $test_fqdn[$domain_purchased_int],
            'sub_domain' => $test_fqdn[$domain_sub_int],
            'tld' => $test_fqdn[$domain_tld_int],
            'is_icann' => $is_icann
        );
    }
}
