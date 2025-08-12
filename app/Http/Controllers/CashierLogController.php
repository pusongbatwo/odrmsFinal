<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashierLog;

class CashierLogController extends Controller
{
    public function index()
    {
        $logs = CashierLog::orderBy('created_at', 'desc')->paginate(20);
        return view('cashier.system_logs', compact('logs'));
    }
}
