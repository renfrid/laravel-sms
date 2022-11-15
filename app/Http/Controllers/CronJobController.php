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

                    //sms log
                    $sms_log = SmsLog::findOrFail($val->id);

                    //check for errors
                    if ($result->error == 'Invalid request_id or dest_addr') {
                        //change status to  DELIVERED
                        $sms_log->status = "DELIVERED";
                    } else {
                        //change status to DELIVERED or REJECTED
                        $sms_log->status = $result->status;
                    }
                    //save
                    $sms_log->save();
                }
            }
        }
        echo response()->json(["error" => false, "success_msg" => "Delivery report success!"]);
    }
}
