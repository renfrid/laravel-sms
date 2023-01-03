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

    function index(Request $request)
    {
        $title = 'Dashboard';

        //declare variable
        $all_sms = 0;
        $all_delivered_sms = 0;
        $all_pending_sms = 0;
        $all_undelivered_sms = 0;

        if (isset($_POST['filter'])) {
            //post variable
            $start_date = $request->input('start_at');
            $end_date = $request->input('end_at');

            //start data and end date
            if ($start_date != null && $end_date != null) {
                $start_at = date('Y-m-d', strtotime($start_date));
                $end_at = date('Y-m-d', strtotime($end_date));

                //total sms
                $all_sms = SmsLog::whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();

                //delivered sms
                $all_delivered_sms = SmsLog::where(['sms_logs.gateway_status' => 'DELIVERED'])
                    ->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();

                //pending sms
                $all_pending_sms = SmsLog::where(['sms_logs.gateway_status' => 'PENDING'])
                    ->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();

                //undelivered sms
                $all_undelivered_sms = SmsLog::where(['sms_logs.gateway_status' => 'UNDELIVERED'])
                    ->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();
            }
        } else {
            $start_date = null;
            $end_date = null;

            //total sms
            $all_sms = SmsLog::count();

            //delivered sms
            $all_delivered_sms = SmsLog::where(['gateway_status' => 'DELIVERED'])->count();

            //pending sms
            $all_pending_sms = SmsLog::where(['gateway_status' => 'PENDING'])->count();

            //undelivered sms
            $all_undelivered_sms = SmsLog::where(['gateway_status' => 'UNDELIVERED'])->count();
        }

        //construct data
        $data = [
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_sms' => $all_sms,
            'delivered_sms' => $all_delivered_sms,
            'pending_sms' => $all_pending_sms,
            'undelivered_sms' => $all_undelivered_sms
        ];

        //render view
        return view('dashboard', compact('title', 'data'));
    }
}
