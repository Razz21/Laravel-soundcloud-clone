<?php

namespace App\Helpers;

class Utils
{
    public static function is_json($raw_json)
    {
        return json_decode($raw_json, true) ?? false;
    }
}
