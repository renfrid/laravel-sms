<?php

namespace App\Http\Controllers;

use App\Models\SmsLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $title = 'Dashboard';

        //total sms
        $total_sms = SmsLog::count();

        //delivered sms
        $delivered_sms = SmsLog::where(['status' => 'DELIVERED'])->count();

        //pending sms
        $pending_sms = SmsLog::where(['status' => 'PENDING'])->count();

        //rejected sms
        $rejected_sms = SmsLog::where(['status' => 'REJECTED'])->count();

        //render view
        return view('dashboard', compact('title', 'total_sms','delivered_sms', 'pending_sms', 'rejected_sms'));
    }
}
