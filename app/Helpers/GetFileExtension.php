<?php

namespace App\Helpers;

class GetFileExtension
{
    public static function run($path): string
    {
        return pathinfo($path, PATHINFO_EXTENSION);
    }
}
