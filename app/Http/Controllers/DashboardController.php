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

        //sms logs
        $sms_logs = SmsLog::orderBy('created_at');

        if (isset($_POST['filter'])) {
            //post variable
            $start_at = $request->input('start_at');
            $end_at = $request->input('end_at');

            //start data and end date
            if ($start_at != null && $end_at != null) {
                $start_at = date('Y-m-d', strtotime($start_at));
                $end_at = date('Y-m-d', strtotime($end_at));

                //total sms
                $all_sms = $sms_logs->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();

                //delivered sms
                $all_delivered_sms = $sms_logs->where(['sms_logs.gateway_status' => 'DELIVERED'])
                    ->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();

                //pending sms
                $all_pending_sms = $sms_logs->where(['sms_logs.gateway_status' => 'PENDING'])
                    ->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();

                //undelivered sms
                $all_undelivered_sms = $sms_logs->where(['sms_logs.gateway_status' => 'UNDELIVERED'])
                    ->whereBetween('sms_logs.created_at', [$start_at, $end_at])
                    ->count();
            }
        } else {
            //total sms
            $all_sms = $sms_logs->count();

            //delivered sms
            $all_delivered_sms = $sms_logs->where(['gateway_status' => 'DELIVERED'])->count();

            //pending sms
            $all_pending_sms = $sms_logs->where(['gateway_status' => 'PENDING'])->count();

            //undelivered sms
            $all_undelivered_sms = $sms_logs->where(['gateway_status' => 'UNDELIVERED'])->count();
        }

        //construct data
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
