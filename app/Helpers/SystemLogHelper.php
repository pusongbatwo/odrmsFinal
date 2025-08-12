<?php
namespace App\Helpers;
use App\Models\SystemLog;

class SystemLogHelper
{
    public static function log($type, $message)
    {
        SystemLog::create([
            'type' => $type,
            'message' => $message,
        ]);
    }
}
