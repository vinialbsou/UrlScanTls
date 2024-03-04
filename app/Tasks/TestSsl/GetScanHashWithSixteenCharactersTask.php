<?php

namespace App\Tasks\TestSsl;

use Illuminate\Http\Request;

class GetScanHashWithSixteenCharactersTask
{
    /**
     * @param Request $request
     * @return string
     */
    public static function run(Request $request): string
    {
        $scanHash = substr(md5(json_encode($request->all())), 0, 16);
        return  config('tlsscan.cache.prefixName', 'tlsscan') . $scanHash;
    }
}
