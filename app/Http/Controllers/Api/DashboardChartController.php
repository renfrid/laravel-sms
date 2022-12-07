<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SmsLog;
use Illuminate\Http\Request;

class DashboardChartController extends Controller
{
    //sms status monthly
    function monthly_status()
    {
        $month_lists = [];
        $total_sms_lists = [];
        $pending_sms_lists = [];
        $delivered_sms_lists = [];
        $rejected_sms_lists = [];

        //sms query
        for ($i = 0; $i <= 11; $i++) {
            $year_month = date("Y-m", strtotime(date('Y-m-01') . " -$i months"));
            $month = date('m', strtotime($year_month));
            $year = date('Y', strtotime($year_month));

            //query sms
            $total_sms = SmsLog::whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();
            $pending_sms = SmsLog::where('gateway_status', '=', 'PENDING')->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();
            $delivered_sms = SmsLog::where('gateway_status', '=', 'DELIVERED')->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();
            $rejected_sms = SmsLog::where('gateway_status', '=', 'UNDELIVERED')->whereYear('created_at', '=', $year)->whereMonth('created_at', '=', $month)->count();

            //month_lists
            $month_lists[] = date("F", mktime(0, 0, 0, $month, 10)) . ',' . $year;
            $total_sms_lists[] = (int) $total_sms;
            $pending_sms_lists[] = (int) $pending_sms;
            $delivered_sms_lists[] = (int) $delivered_sms;
            $rejected_sms_lists[] = (int) $rejected_sms;
        }

        $data_array = [
            'month' => $month_lists,
            'total_sms' => $total_sms_lists,
            'pending_sms' => $pending_sms_lists,
            'delivered_sms' => $delivered_sms_lists,
            'rejected_sms' => $rejected_sms_lists
        ];

        //response
        echo json_encode(['error' => FALSE, 'data' => $data_array], 200);
    }
}
