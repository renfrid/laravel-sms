<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Messaging;
use App\Models\SmsLog;
use Illuminate\Support\Facades\DB;

class CronJobController extends Controller
{
    private $messaging;
    public function __construct()
    {
        //messaging
        $this->messaging = new Messaging();
    }

    //send sms
    function send_sms()
    {
        //limit
        $limit = 2000;

        //date range
        $start_at = date('2022-12-19');
        $end_at = date('2022-12-21');

        //recipients
        $recipients = SmsLog::select('id', 'phone', 'message', 'sender')
            ->where('schedule', '=', null)
            ->where(function ($query) {
                $query->where('gateway_status', '=', 'UNDELIVERED');
            })->whereBetween('sms_logs.created_at', [$start_at, $end_at])->take($limit)->get();

        if ($recipients->isNotEmpty()) {
            foreach ($recipients as $val) {
                //create arr data
                $postData = array(
                    'source_addr' => $val->sender,
                    'encoding' => 0,
                    'schedule_time' => '',
                    'message' => $val->message,
                    'recipients' => [array('recipient_id' => 1, 'dest_addr' => $this->messaging->castPhone($val->phone))]
                );

                //post data
                $response = $this->messaging->sendSMS($postData);
                $result = json_decode($response);

                //check for successful or failure of message
                if ($result->code == 100) {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_id = $result->request_id;
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "SENT";
                    $sms_log->gateway_status = "SENT";
                    $sms_log->sent_at = date('Y-m-d H:i:s');
                    $sms_log->save();

                    //TODO: deduct bundle
                } else {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "REJECTED";
                    $sms_log->gateway_status = "REJECTED";
                    $sms_log->sent_at = date('Y-m-d H:i:s');
                    $sms_log->save();
                }
            }
        }

        //print message
        echo json_encode(['error' => false, "success_msg" => "Message sent to sms gateway"]);
    }

    //send scheduled sms
    function send_scheduled_sms()
    {
        //current date
        $current_date = date('Y-m-d H:i:s');

        //limit
        $limit = 2000;

        //recipients
        $recipients = SmsLog::select('id', 'phone', 'message', 'sender')
            ->where('schedule', '=', 1)
            ->where('schedule_at', '<=', $current_date)
            ->where(function ($query) {
                $query->where('gateway_status', '=', 'PENDING');
            })->take($limit)->get();

        echo "<pre>";
        print_r($recipients);

        if ($recipients->isNotEmpty()) {
            foreach ($recipients as $val) {
                //create arr data
                $postData = array(
                    'source_addr' => $val->sender,
                    'encoding' => 0,
                    'schedule_time' => '',
                    'message' => $val->message,
                    'recipients' => [array('recipient_id' => 1, 'dest_addr' => $this->messaging->castPhone($val->phone))]
                );

                //post data
                $response = $this->messaging->sendSMS($postData);
                $result = json_decode($response);

                //check for successful or failure of message
                if ($result->code == 100) {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_id = $result->request_id;
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "SENT";
                    $sms_log->gateway_status = "SENT";
                    $sms_log->sent_at = date('Y-m-d H:i:s');
                    $sms_log->save();

                    //TODO: deduct bundle
                } else {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "REJECTED";
                    $sms_log->gateway_status = "REJECTED";
                    $sms_log->sent_at = date('Y-m-d H:i:s');
                    $sms_log->save();
                }
            }
        }

        //print message
        echo json_encode(['error' => false, "success_msg" => "Message sent to sms gateway"]);
    }

    //delivery report
    function delivery_report()
    {
        //date range
        $start_at = date('2022-12-19');
        $end_at = date('2022-12-21');

        //->orWhere(['gateway_status' => 'PENDING']);

        $limit = 10;

        //take all recipients for delivery report
        $recipients = SmsLog::select('id', 'gateway_id', 'phone')->where(function ($query) {
            $query->where(['gateway_status' => 'SENT']);
        })->whereBetween('sms_logs.created_at', [$start_at, $end_at])->take($limit)->get();

        foreach ($recipients as $val) {
            //create arr data
            if ($val->gateway_id != null) {
                $postData = array(
                    'request_id' => $val->gateway_id,
                    'dest_addr' => $this->messaging->castPhone($val->phone)
                );

                echo "<pre>";
                print_r($postData);

                //post data
                $response = $this->messaging->deliveryReport($postData);
                $result = json_decode($response);

                echo "<pre>";
                print_r($result);

                //sms log
                $sms_log = SmsLog::findOrFail($val->id);

                //check for errors
                if (isset($result->error)) {
                    if ($result->error == 'Invalid request_id or dest_addr') {
                        //change status to  DELIVERED
                        $sms_log->gateway_status = "PENDING";
                        $sms_log->delivered_at = date('Y-m-d H:i:s');
                    }
                } else {
                    if (isset($result->status)) {
                        //change status to DELIVERED or UNDELIVERED or PENDING
                        $sms_log->gateway_status = $result->status;
                        $sms_log->delivered_at = date('Y-m-d H:i:s');
                    }
                }
                //save
                $sms_log->save();
            }
        }

        //response
        echo response()->json(["error" => false, "success_msg" => "Delivery report success!"]);
    }
}
