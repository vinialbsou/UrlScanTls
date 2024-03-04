<?php

namespace App\Helpers;

class CheckIfOptionsSettingExistHelper
{
    /**
     * If one of those option setting by user, not found in our config
     * Will return false and get a throw Exception
     * @param $optionsSetting
     * @return bool
     */
    public static function run($optionsSetting): bool
    {
        $optionsSettingArray = explode(',', $optionsSetting);

        foreach($optionsSettingArray as $option){
            if(!in_array($option, config('tlsscan.testsslParametersOptionalForUser'))){
               return false;
            }
        }

        return true;
    }
}
