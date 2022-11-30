<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\Messaging;
use App\Models\SmsLog;

class CronJobController extends Controller
{
    private $messaging;
    public function __construct()
    {
        //messaging
        $this->messaging = new Messaging();
    }


    //send sms process
    function send_process()
    {
        //limit
        $limit = 100;
        $no_of_pending_sms = SmsLog::where(['status' => 'PENDING'])->count();

        //looping sms
        $looping = $no_of_pending_sms / $limit;

        //iterate looping
        for ($i = 1; $i <= ceil($looping); $i++) {
            $recipients = SmsLog::select('id', 'phone', 'message', 'sender', 'schedule', 'schedule_at')->where(['status' => 'PENDING'])->take($limit)->get();

            foreach ($recipients as $val) {
                //create arr data
                $postData = array(
                    'source_addr' => $val->sender,
                    'encoding' => 0,
                    'message' => $val->message,
                    'recipients' => [array('recipient_id' => 1, 'dest_addr' => $this->messaging->castPhone($val->phone))]
                );

                //check for schedule
                if ($val->schedule == 1)
                    $postData['schedule_time'] = $val->schedule_at;
                else
                    $postData['schedule_time'] = '';

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
                    $sms_log->save();

                    //TODO: deduct bundle
                } else {
                    //update sms status
                    $sms_log = SmsLog::findOrFail($val->id);
                    $sms_log->gateway_response = json_encode($result);
                    $sms_log->gateway_code = $result->code;
                    $sms_log->gateway_message = $result->message;
                    $sms_log->status = "REJECTED";
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
        //limit
        $limit = 100;
        $no_of_sent_sms = SmsLog::where(['status' => 'SENT'])->count();

        //looping sms
        $looping = $no_of_sent_sms / $limit;

        //iterate looping
        for ($i = 1; $i <= ceil($looping); $i++) {
            $recipients = SmsLog::select('id', 'gateway_id', 'phone')->where(['status' => 'SENT'])->take($limit)->get();

            foreach ($recipients as $val) {
                //create arr data
                if ($val->gateway_id != null) {
                    $postData = array(
                        'request_id' => $val->gateway_id,
                        'dest_addr' => $this->messaging->castPhone($val->phone)
                    );

                    //post data
                    $response = $this->messaging->deliveryReport($postData);
                    $result = json_decode($response);

                    echo "<pre>";
                    print_r($result);

                    //sms log
                    $sms_log = SmsLog::findOrFail($val->id);

                    // //check for errors
                    // if ($result->error == 'Invalid request_id or dest_addr') {
                    //     //change status to  DELIVERED
                    //     $sms_log->status = "DELIVERED";
                    // } else {
                    //     //change status to DELIVERED or REJECTED
                    //     $sms_log->status = $result->status;
                    // }
                    //save
                    //$sms_log->save();
                }
            }
        }
        echo response()->json(["error" => false, "success_msg" => "Delivery report success!"]);
    }
}
