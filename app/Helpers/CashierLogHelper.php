<?php

namespace App\Helpers;

use App\Models\CashierLog;
use Illuminate\Support\Facades\Auth;

class CashierLogHelper
{
    public static function log($type, $message)
    {
        $user = Auth::user();
        $userInfo = $user ? ("[Cashier: {$user->name} ({$user->email})]") : '';
        CashierLog::create([
            'type' => $type,
            'message' => $userInfo . ' ' . $message,
        ]);
    }
}
