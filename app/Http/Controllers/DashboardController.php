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
        $all_sms = SmsLog::count();

        //delivered sms
        $all_delivered_sms = SmsLog::where(['status' => 'DELIVERED'])->count();

        //pending sms
        $all_pending_sms = SmsLog::where(['status' => 'PENDING'])->count();

        //undelivered sms
        $all_undelivered_sms = SmsLog::where(['status' => 'UNDELIVERED'])->count();

        $data = [
            'total_sms' => $all_sms,
            'delivered_sms' => $all_delivered_sms,
            'pending_sms' => $all_pending_sms,
            'undelivered_sms' => $all_undelivered_sms
        ];

        //render view
        return view('dashboard', compact('title', 'data'));
    }
}
